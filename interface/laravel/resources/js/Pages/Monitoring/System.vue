<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>


<template>
    <AppLayout title="Dashboard">
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-lg sm:rounded-lg">
                    <iframe name="grafana_dashboard" src="https://good-spiders.com/grafana/d/vyKErBV4z/jarvis-monitoring?kiosk" id="grafana_dashboard" sandbox="allow-same-origin allow-scripts" width=100% height=800 class="sm:rounded-lg shadow-lg"></iframe>
                </div>
            </div>
        </div>
    </AppLayout>
</template>