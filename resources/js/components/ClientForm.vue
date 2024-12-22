<template>
    <div>
        <h1 class="mb-6">Clients -> Add New Client</h1>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="client.name">
                <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" class="form-control" v-model="client.email">
                <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" class="form-control" v-model="client.phone">
                <span v-if="errors.phone" class="text-danger">{{ errors.phone[0] }}</span>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" class="form-control" v-model="client.address">
            </div>
            <div class="flex">
                <div class="form-group flex-1">
                    <label for="city">City</label>
                    <input type="text" id="city" class="form-control" v-model="client.city">
                </div>
                <div class="form-group flex-1">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" class="form-control" v-model="client.postcode">
                </div>
            </div>

            <div class="text-right">
                <a :href="getClientsIndexUrl()" class="btn btn-default">Cancel</a>
                <button @click="storeClient" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { urlMixin } from '../mixins/urlMixin';
import { emailValidationReg, phoneValidationReg } from '../helpers/validation';

export default {
    name: 'ClientForm',
    mixins: [urlMixin],
    data() {
        return {
            client: {
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                postcode: '',
            },
            errors: {}
        }
    },
    methods: {
        validateForm() {
            this.errors = {};

            if (!this.client.name) {
                this.errors.name = ['Name is required.'];
            } else if (this.client.name.length > 190) {
                this.errors.name = ['Name cannot exceed 190 characters.'];
            }

            if (this.client.email && !emailValidationReg.test(this.client.email)) {
                this.errors.email = ['The email must be a valid email address.'];
            }

            if (this.client.phone && !phoneValidationReg.test(this.client.phone)) {
                this.errors.phone = ['The phone number can only contain digits, spaces, and a plus sign.'];
            }

            if (!this.client.email && !this.client.phone) {
                this.errors.email = ['At least one of phone or email is required.'];
            }

            return Object.keys(this.errors).length === 0;
        },

        storeClient() {
            // I make a validation on the front-end and on the back-end.
            // The front-end validation is for the user experience and the back-end validation is for the security and stability.
            if (!this.validateForm()) {
                return;
            }

            axios.post('/api/clients', this.client)
                .then((response) => {
                    window.location.href = response.data.data.url;
                })
                .catch((error) => {
                    if (error.response && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    }
                });
        }
    }
}
</script>
