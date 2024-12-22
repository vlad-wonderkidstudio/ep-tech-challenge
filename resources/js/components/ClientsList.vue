<template>
    <div>
        <h1>
            Clients
            <a :href="getClientCreateUrl()" class="float-right btn btn-primary">+ New Client</a>
        </h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Number of Bookings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in currentClients" :key="client.id">
                    <td>{{ client.name }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.phone }}</td>
                    <td>{{ client.bookings_count }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" :href="getClientShowUrl(client.id)">View</a>
                        <button class="btn btn-danger btn-sm" @click="deleteClient(client)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import * as api from '../helpers/api';
import { urlMixin } from '../mixins/urlMixin';

export default {
    name: 'ClientsList',
    mixins: [urlMixin],
    props: ['clients'],
    data() {
        return {
            localClients: undefined,
        }
    },
    computed: {
        currentClients() {
            return this.localClients || this.clients;
        }
    },
    methods: {
        deleteClient(client) {
            api.deleteClient(client.id)
            .then(response => {
                //Note: I see 5 ways how to make it
                // 1. With just filtering the clients array and remove the "client" - but if the user will have 2 open tabs this may lead to inconsistency
                // 2. The way with reloading the page - window.location.reload() - decrease the user experience
                // 3. By adding an API call to get all clients again
                // 4. Make ClientsController return the updated list of clients - but this is not a good practice
                // 5. By using Vuex - but this is an overkill for this simple application
                // I use a hybrid approach of 1 and 3 here - in order to make it as smooth as possible
                this.localClients = this.currentClients.filter(c => c.id !== client.id);
            })
            .catch(error => {
                console.error('There was an error deleting the client:', error);
            })
            .finally(() => {
                this.fetchClients();
            });
        },
        fetchClients() {
            api.getClients()
            .then(response => {
                if (response.data?.data) {
                    this.localClients = response.data.data;
                }
            })
            .catch(error => {
                console.error('There was an error fetching the clients:', error);
            });
        }
    }
}
</script>
