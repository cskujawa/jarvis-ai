<script setup>
/*
    This template utilizes several different Vue Components and leverages axios for API calls
    On page load it will make an API call to an internal API Router found at /interface/laravel/routes/api.php
    Using the drop-downs will trigger events routed by that API handler, for more info view that file.
*/
import AppLayout from '@/Layouts/AppLayout.vue';
import JetLabel from '@/Jetstream/Label.vue';
import vSelect from 'vue-select';
import { ElProgress } from 'element-plus';
import { ElCarousel } from 'element-plus';
import { ElCarouselItem } from 'element-plus';
import { ElCollapse } from 'element-plus';
import { ElCollapseItem } from 'element-plus';
import { ElCard } from 'element-plus';
import { ElBadge } from 'element-plus'; 
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
                            v-model="inputs.year" 
                            class="vselect-style"
                            placeholder="Select a year"
                            :options="options.years"
                            label="year"
                            resetOnOptionsChange = true;
                        ></vSelect>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Make
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            v-model="inputs.make"
                            class="vselect-style"
                            placeholder="Select a make"
                            :disabled="inputs.year === null || inputs.year.length === 0"
                            :options="options.makes"
                            label="name"
                            resetOnOptionsChange = true;
                        ></vSelect>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Model
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            v-model="inputs.model"
                            class="vselect-style"
                            placeholder="Select a model"
                            :disabled="inputs.make === null || inputs.make.length === 0"
                            :options="options.models"
                            label="name"
                            resetOnOptionsChange = true;
                        ></vSelect>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <JetLabel>
                            Variant
                        </JetLabel>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:items-center sm:flex">
                        <vSelect style="width:175px"
                            v-model="inputs.variant"
                            class="vselect-style"
                            placeholder="Select a variant"
                            :disabled="inputs.model === null || inputs.model.length === 0"
                            :options="options.variants"
                            label="name"
                            resetOnOptionsChange = true;
                        ></vSelect>
                    </div>
                </div>
            </div>
            <!-- Car Data -->
            <div v-if="profile != null" class="flex justify-between mt-2 overflow-auto bg-black shadow-lg sm:rounded-lg" style="height: auto">
                <div style="width:50%; float:left">
                    <table title="Car Description" class="text-white">
                        <tbody>
                            <thead>Safety Ratings from <a href="https://www.nhtsa.gov/about-nhtsa" target="_blank">NHTSA.gov</a></thead>
                            <tr>
                                <td>{{profile["Description"]}}</td>
                            </tr>
                            <tr style="vertical-align: baseline;">
                                <td><img :src="profile['Picture']"></td>
                            </tr>
                            <br>
                            <tr v-for="(value, key) in profile['Ratings']" style="vertical-align: baseline;">
                                <td>
                                    {{key}}: 
                                    <ElProgress
                                        :text-inside="true"
                                        :stroke-width="20"
                                        :percentage=value*20
                                        :color="
                                            value < 2 ? '#e14848'
                                            : value < 4 ? '#dd8501'
                                            : '#37dd01'
                                        "
                                        >
                                        <span>{{value}}/5</span>
                                    </ElProgress>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div  class="text-white" style="width:50%">
                    <ElCollapse>
                        <ElCollapseItem title="Crash Test Images">
                            <ElCarousel trigger="click" height="500px" arrow=always :autoplay=false>
                                <ElCarouselItem v-for="picture in profile['CrashTests']" style="display:flex">
                                    <img :src=picture>
                                </ElCarouselItem>
                            </ElCarousel>
                        </ElCollapseItem>
                    </ElCollapse>

                    <div v-for="(value, key) in profile['NHTSAStats']">
                        <div style="width:33%; float:left;">
                            <ElBadge :value=value>
                                <ElCard>
                                    {{ key }}
                                </ElCard>
                            </ElBadge>
                        </div>
                    </div>

                    <table title="Safety Features" class="text-white">
                        <tbody v-for="(value, key) in profile['SafetyFeatures']">
                            <tr>
                                <td>{{ key }}: </td>
                                <td>{{ value }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table title="Sales Data" class="text-white">
                        <tbody v-for="(value, key) in profile['SalesData']">
                            <tr>
                                <td>{{ key }}: </td>
                                <td>{{ value }}</td>
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
        data() {
            return {
                //Array for storing user input
                inputs: {
                    year: [],
                    make: [],
                    model: [],
                    variant: []
                },
                //Array for storing drop-down options
                options: {
                    years: [],
                    makes: [],
                    models: [],
                    variants: []
                },
                //Variable containing all data for a car
                profile: null,
            }
        },

        watch: {
            //Watching for changes on the year drop-down
            'inputs.year'(selection){
                //If a selection is made, make a call to the internal API to get options for the subsequent drop-down
                if(selection != null){
                    console.log("Selected year: " + selection['year']);
                    this.request("/api/nhtsa?"
                        + "options=makes"
                        + "&id=" + selection['id']
                    );
                }
            },
            //Watching for changes on the make drop-down
            'inputs.make'(selection){
                //If a selection is made, make a call to the internal API to get options for the subsequent drop-down
                if(selection != null){
                    console.log("Selected make: " + selection['name'] + " Selected year: " + this.inputs.year['year']);
                    this.request("/api/nhtsa?"
                        + "options=models"
                        + "&id=" + selection['id']
                        + "&year=" + this.inputs.year['year']
                    );
                }
            },
            //Watching for changes on the model drop-down
            'inputs.model'(selection){
                //If a selection is made, make a call to the internal API to get options for the subsequent drop-down
                if(selection != null){
                    console.log("Selected make: " + this.inputs.make['name'] + " Selected year: " + this.inputs.year['year'] + " Selected model: " + this.inputs.model['name']);
                    this.request("/api/nhtsa?"
                        + "options=variants"
                        + "&id=" + selection['id']
                        + "&year=" + this.inputs.year['year']
                        + "&make=" + this.inputs.make['name']
                    );
                }
            },
            //Watching for changes on the variant drop-down
            'inputs.variant'(selection){
                //If a selection is made, make a call to the internal API to get options for the subsequent drop-down
                if(selection != null){
                    console.log("Selected make: " + this.inputs.make['name'] + " Selected year: " + this.inputs.year['year'] + " Selected model: " + this.inputs.model['name'] + " Selected variant: " + this.inputs.variant['name']);
                    this.request("/api/nhtsa?"
                        + "options=profile"
                        + "&id=" + selection['id']
                    );
                }
            }
        },

        //Function to load the years available on page load
        mounted() {
            this.request("/api/nhtsa?options=years");
        },

        methods: {
            //Request Handler, takes any request and switches where data is stored based on the request query param
            request($query){
                axios.get($query)
                    .then((response) => {
                        console.log("Request Handler", response.data );
                        switch(response.data['request']) {
                            case 'years':
                                this.options.years = response.data['results'];
                                break;
                            case 'makes':
                                this.options.makes = response.data['results'];
                                break;
                            case 'models':
                                this.options.models = response.data['results'];
                                break;
                            case 'variants':
                                this.options.variants = response.data['results'];
                                break;
                            case 'profile':
                                this.profile = response.data['results'];
                                break;
                        }
                    })
                    .catch( error => {
                        console.log('Internal API Error:');
                        console.log(error);
                    });
            }
        }
    }
</script>