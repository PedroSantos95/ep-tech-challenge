<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th class="text-gray-600 pr-3">Name</th>
                                <td>{{ client.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Email</th>
                                <td>{{ client.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Phone</th>
                                <td>{{ client.phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Address</th>
                                <td>
                                    {{ client.address }}<br/>
                                    {{ client.postcode || '' }} {{ client.city || '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn" :class="{'btn-primary': currentTab === 'bookings', 'btn-default': currentTab !== 'bookings'}" @click="switchTab('bookings')">Bookings</button>
                    <button class="btn" :class="{'btn-primary': currentTab === 'journals', 'btn-default': currentTab !== 'journals'}" @click="switchTab('journals')">Journals</button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab === 'bookings'">
                    <h3 class="mb-3">List of client bookings</h3>

                    <div class="mb-5">
                        <select  v-model="bookingFilter" class="form-control">
                            <option value="">All bookings</option>
                            <option value="future">Future bookings only</option>
                            <option value="past">Past bookings only</option>
                        </select>
                    </div>

                    <template v-if="client.bookings && client.bookings.length > 0">
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
                                    <td>{{ booking.formatted_date }}</td>
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

                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab === 'journals'">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>List of client journals</h3>
                        <a :href="`/clients/${client.id}/journals/create`" class="btn btn-primary">+ New Journal Entry</a>
                    </div>
                    <template v-if="client.journals && client.journals.length > 0">
                        <table class="mt-400">
                            <thead>
                            <tr>
                                <th class="px-3">Date</th>
                                <th class="w-100 px-3">Content</th>
                                <th class="px-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="journal in updatedClientJournalsList" :key="journal.id">
                                <td class="px-3">
                                    <p class="text-nowrap">
                                        {{ journal.date }}
                                    </p>
                                </td>
                                <td class="px-3">
                                    <p>
                                        {{ journal.text }}
                                    </p>
                                </td>
                                <td class="px-3">
                                    <button class="btn btn-danger btn-sm" @click="deleteJournalEntry(journal)">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientShow',

    props: ['client'],

    data() {
        return {
            currentTab: 'bookings',
            bookingFilter: '',
            updatedClientJournalsList: this.client.journals
        }
    },

    computed: {
        filteredBookings() {
            const now = new Date();
            return this.client.bookings.filter(booking => {
                const bookingDate = new Date(booking.start);
                if (this.bookingFilter === 'future') {
                    return bookingDate >= now;
                }
                if (this.bookingFilter === 'past') {
                    return bookingDate < now;
                }
                return true;
            });
        },
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;
        },

        deleteBooking(booking) {
            axios.delete(`/bookings/${booking.id}`);
        },

        deleteJournalEntry(journal) {
            axios.delete(`/clients/${this.client.id}/journals/${journal.id}`)
                .then(({ data }) => {
                    this.updatedClientJournalsList = this.updatedClientJournalsList.filter(b => b.id !== journal.id);
                    this.$toast.success(data.message || "Journal deleted successfully!");
                })
                .catch((error) => {
                    this.$toast.error("An error occurred while deleting the journal.");
                });
        }
    }
}
</script>
