<template>
    <div>
        <h1 class="mb-6">Clients -> Add New Journal Entry</h1>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="name">Date</label>
                <input type="date" id="date" class="form-control" v-model="journal.date">
                <span class="text-red-600" v-for="error in errors?.date">{{ error }}</span>
            </div>
            <div class="form-group">
                <label for="text">Text</label>
                <textarea id="text" class="form-control" v-model="journal.text"></textarea>
                <span class="text-red-600" v-for="error in errors?.text">{{ error }}</span>
            </div>

            <div class="text-right">
                <a href="/clients" class="btn btn-default">Cancel</a>
                <button @click="storeJournal" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'JournalForm',

    props: ['client'],

    data() {
        return {
            journal: {
                date: '',
                text: '',
            },
            errors: {}
        }
    },

    methods: {
        storeJournal() {
            axios.post(this.client.url + '/journals', this.journal)
                .then((data) => {
                    window.location.href = `/clients/${this.client.id}`;
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                });
        }
    }
}
</script>
