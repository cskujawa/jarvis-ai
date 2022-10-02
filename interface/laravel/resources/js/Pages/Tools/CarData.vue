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
                            resetOnOptionsChange = true;
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
                            resetOnOptionsChange = true;
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
                            resetOnOptionsChange = true;
                            @option:selected="modelSelected"
                            @option:deselected="modelSelected"
                        ></vSelect>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Variant
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            class="vselect-style"
                            placeholder="Select a variant"
                            :disabled="model === null"
                            :options="variants"
                            label="VehicleDescription"
                            resetOnOptionsChange = true;
                            @option:selected="variantSelected"
                            @option:deselected="variantSelected"
                        ></vSelect>
                    </div>
                </div>
            </div>
            <!-- Car Data -->
            <div class="flex justify-between mt-2 overflow-auto bg-black shadow-lg sm:rounded-lg" style="height: auto">
                <div class="flex">
                    <table title="Car Description" class="text-white">
                        <tbody v-if="safety.length">
                            <tr>
                                <td>{{safety[0]["VehicleDescription"]}}</td>
                            </tr>
                            <tr>
                                <td><img :src="safety[0]["VehiclePicture"]" :alt="null"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex">
                    <table title="Raw Data"  id="carData" class="text-white">
                        <tbody v-for="row in safety">
                            <tr v-for="key in keys">
                                <td>{{key}}</td>
                                <td>{{row[key]}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>

    export default {
        //Variable to store data fetched from NHTSA
        data() {
            return {
                years: [],
                year: null,
                makes: [],
                make: null,
                models: [],
                model: null,
                variants: [],
                variant: null,
                safety: [],
                description: []
            }
        },

        //Function to load the years available on page load
        mounted() {
            this.getYears();
        },

        methods: {
            //Function to get available years
            getYears(){
                console.log('getYears');
                axios.get('/api/nhtsa/years')
                    .then((response) => {
                        console.log( response.data );
                        this.years = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    });
            },
            yearSelected(value) {
                console.log(value.ModelYear);
                //Set the select
                this.year = value.ModelYear;
                //Get the options for the subsequent select
                this.getMakes()
                //Clear the subsequent selects
                this.make = null;
                this.makes = [];
                this.models = [];
                this.variants = [];
                this.model = null;
                this.variant = null;
                //Clear data
                this.safety = [];
            },

            //Function to get available models by year
            getMakes(){
                console.log('getMakes');
                axios.post('/api/nhtsa/makes', {params: { "year": this.year }})
                    .then((response) => {
                        console.log( response.data );
                        this.makes = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    });
                //Clear the subsequent selects
                this.model = null;
                this.variant = null;
                this.models = [];
                this.variants = [];
                //Clear data
                this.safety = [];
            },
            makeSelected(value) {
                console.log(value.Make);
                //Set the select
                this.make = value.Make;
                //Get the options for the subsequent select
                this.getModels()
            },

            //Function to get available makes by model and year
            getModels(){
                console.log('getModels');
                axios.post('/api/nhtsa/models', {params: { "year": this.year, "make": this.make }})
                    .then((response) => {
                        console.log( response.data );
                        this.models = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    });
                //Clear the subsequent selects
                this.model = null;
                this.variant = null;
                this.models = [];
                this.variants = [];
                //Clear data
                this.safety = [];
            },
            modelSelected(value) {
                console.log(value.Model);
                //Set the select
                this.model = value.Model;
                //Get the options for the subsequent select
                this.getVariants()
            },

            //Function to get available variants by make, model, and year
            getVariants(){
                var delim = this.model.concat(" ");
                console.log('getVariants');
                axios.post('/api/nhtsa/variants', {params: { "year": this.year, "make": this.make, "model": this.model }})
                    .then((response) => {
                        var variants = response.data;
                        console.log( response.data );
                        for (const variant of variants) {
                            variant.VehicleDescription = variant.VehicleDescription.toUpperCase().split(delim)[1];
                        }
                        this.variants = variants;
                    })
                    .catch( response => {
                        console.log('error');
                    });
                //Clear data
                this.safety = [];
            },
            variantSelected(value) {
                console.log(value.VehicleId);
                //Set the select
                this.variant = value.VehicleId;
                //Get data
                this.getSafety()
            },

            //Function to get available data
            getSafety(){
                console.log('getSafety');
                axios.post('/api/nhtsa/safety', {params: { "id": this.variant }})
                    .then((response) => {
                        console.log( response.data );
                        this.safety = response.data;
                    })
                    .catch( response => {
                        console.log('error');
                    })
            }
        },
        computed: {
            "keys": function columns() {
                if (this.safety.length == 0) {
                    return [];
                }
                return Object.keys(this.safety[0])
            }
        }
    }
</script>