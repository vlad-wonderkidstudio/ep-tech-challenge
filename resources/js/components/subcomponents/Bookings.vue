<template>
    <span>
        <h3 class="mb-3">List of client bookings</h3>
        <div class="align-right mb-3">
            <select v-model="bookingSelection">
                <option
                    v-for="option in bookingSelectionOptions"
                    :key="option.value"
                    :value="option.value"
                    :selected="option.value == bookingSelection"
                >
                    {{ option.label }}
                </option>
            </select>
        </div>

        <template v-if="filteredBookings && filteredBookings.length > 0">

            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in filteredBookings" :key="booking.id">
                        <td>{{ formatDatesRange(booking.start, booking.end) }}</td>
                        <td>{{ booking.notes }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>

        <template v-else>
            <p class="text-center">The client has no bookings.</p>
        </template>
    </span>
</template>
<script>
import * as api from '../../helpers/api';
import { formatDatesRange } from '../../helpers/utils';

export default {
    name: 'Bookings',
    props: ['bookings'],
    data() {
        return {
            bookingSelectionOptions: [
                { value: 'all', label: 'All bookings' },
                { value: 'future', label: 'Future bookings only' },
                { value: 'past', label: 'Past bookings only' },
            ],
            bookingSelection: 'all',
            localBookings: undefined,
        }
    },
    computed: {
        currentBookings() {
            return this.localBookings || this.bookings;
        },
        filteredBookings() {
            return this.currentBookings.filter(booking => {
                const dateNow = new Date();
                if (this.bookingSelection === 'all') {
                    return true;
                } else if (this.bookingSelection === 'future') {
                    return new Date(booking.end) > dateNow;
                } else if (this.bookingSelection === 'past') {
                    return new Date(booking.end) < dateNow;
                }
            });
        }
    },
    methods: {
        deleteBooking(booking) {
            // Note: Here I make in a very simple way - just delete the booking from the client object
            // You can see an another approach in ClientsList.vue, where I use a hybrid approach
            api.deleteBooking(booking.id)
            .then(response => {
                this.localBookings = this.currentBookings.filter(b => b.id !== booking.id);
            })
            .catch(error => {
                console.error('There was an error deleting the booking:', error);
            });
        },
        formatDatesRange,
    }
}
</script>
