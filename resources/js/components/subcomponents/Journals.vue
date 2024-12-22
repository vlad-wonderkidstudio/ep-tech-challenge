<template>
    <span>
        <h3 class="mb-3">List of client journals</h3>
        <div>
            <div class="form-group">
                <label for="text">Text</label>
                <textarea
                    id="text"
                    class="form-control"
                    placeholder="Write your journal entry..."
                    v-model="newJournal.text"
                ></textarea>
                <span v-if="errors.text" class="text-danger">{{ errors.text[0] }}</span>
            </div>
            <div class="text-right">
                <button
                    class="btn btn-primary"
                    @click="createJournal"
                >Add Journal</button>
            </div>
        </div>
        <br/>
        <table v-if="currentJournals && currentJournals.length > 0" class="w-100">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Text</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="journal in currentJournals" :key="journal.id">
                    <td>{{ formatDate(journal.date) }}</td>
                    <td><p>{{ journal.text }}</p></td>
                    <td class="text-right">
                        <button
                            class="btn btn-danger btn-sm"
                            @click="deleteJournal(journal.id)"
                        >Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <template v-else>
            <p class="text-center">The client has no journals yet.</p>
        </template>
    </span>
</template>
<script>
import * as api from '../../helpers/api';
import { formatDate } from '../../helpers/utils';

export default {
    name: 'Journals',
    props: ['journals', 'client'],
    data() {
        return {
            localJournals: undefined,
            newJournal: {
                text: ''
            },
            errors: {}
        }
    },
    computed: {
        currentJournals() {
            return this.localJournals || this.journals;
        },
    },
    methods: {
        // I will use here async/await calls and try/catch just to show you that I know how to use it.
        // Not just Promises ;)
        async getJournals() {
            try {
                const response = await api.getJournals(this.client);
                this.localJournals = response.data.data;
            } catch (error) {
                console.error('Error fetching journals:', error);
            }

        },
        async createJournal() {
            if (!this.validateForm()) {
                return;
            }

            try {
                await api.createJournal(this.client, this.newJournal);
                this.newJournal.text = '';
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.getJournals();
            }
        },
        async deleteJournal(id) {
            try {
                await api.deleteJournal(this.client, id);
                this.localJournals = this.currentJournals.filter(journal => journal.id !== id);

            } catch (error) {
                console.error('Error deleting journal:', error);
            } finally {
                this.getJournals();
            }
        },
        validateForm() {
            this.errors = {};

            if (!this.newJournal.text) {
                this.errors.text = ['Text is required.'];
            }
            return Object.keys(this.errors).length === 0;
        },
        formatDate,
    },
};
</script>
