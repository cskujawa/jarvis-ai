<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select'
import JetLabel from '@/Jetstream/Label.vue';
</script>

<template>
    <AppLayout title="Car Data">
        <!-- Main View -->
        <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8">
            <!-- Selector Bar -->
            <div class="flex justify-between h-16 bg-black shadow-lg sm:rounded-lg">
                <div class="flex">
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Year
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            class="vselect-style"
                            placeholder="Select a year"
                            :options="years"
                            label="ModelYear"
                            @option:selected="yearSelected"
                            @option:deselected="yearSelected"
                        ></vSelect>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Make
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            class="vselect-style"
                            placeholder="Select a make"
                            :disabled="year === null"
                            :options="makes"
                            label="Make"
                            @option:selected="makeSelected"
                            @option:deselected="makeSelected"
                        ></vSelect>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Model
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            class="vselect-style"
                            placeholder="Select a model"
                            :disabled="make === null"
                            :options="models"
                            label="Model"
                            @option:selected="modelSelected"
                            @option:deselected="modelSelected"
                        ></vSelect>
                    </div>
                </div>
            </div>
            <!-- Car Data -->
            <div class="flex justify-between mt-2 h-16 overflow-hidden bg-black shadow-lg sm:rounded-lg">
                <div class="flex">
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Car Data
                        </JetLabel>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
    export default {

        data() {
            return {
                years: [],
                year: null,
                makes: [],
                make: null,
                models: [],
                model: null
            }
        },

        mounted() {
            this.getYears();
        },

        methods: {
            getYears(){
                console.log('getYears');
                axios.get('/api/nhtsa/years')
                    .then((response) => {
                        console.log( response.data );
                        this.years = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    })
            },
            yearSelected(value) {
                console.log(value.ModelYear);
                this.year = value.ModelYear;
                this.getMakes(this.year)
            },
            getMakes(){
                console.log('getMakes');
                axios.post('/api/nhtsa/makes', {params: { "year": this.year }})
                    .then((response) => {
                        console.log( response.data );
                        this.makes = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    })
            },
            makeSelected(value) {
                console.log(value.Make);
                this.make = value.Make;
                this.getModels(this.make, this.year)
            },
            getModels(){
                console.log('getModels');
                axios.post('/api/nhtsa/models', {params: { "year": this.year, "make": this.make }})
                    .then((response) => {
                        console.log( response.data[0] );
                        console.log( response.data );
                        this.models = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    })
            },
            modelSelected(value) {
                console.log(value.Model);
                this.model = value.Model;
            }
        },
    }
</script>