<script >
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    components: {
        AppLayout,
    },
    props: {
        tasks: Array,
    },
    methods: {
        printTable() {
            window.print();
        }
    }
}

</script>
<template>
    <AppLayout title="Task">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               Reporte
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    Reporte de mis tareas
                                </h2>
                                <div class="flex justify-between px-4 mt-2">
                                    <button @click="printTable" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md">
                                        Imprimir
                                    </button>
                                    <a :href="route('task.index')" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md">
                                        Regresar
                                    </a>
                                </div>
                                <div class="md:col-span-3 mt-5 md:mt-0">
                                    <div class="shadow bg-white md:rounded-md p-4">
                                        <hr class="my-6">
                                        <div>
                                            <!-- Tabla de Tareas -->
                                            <table class="w-full table-auto">
                                                <thead>
                                                <tr>
                                                    <th class="px-4 py-2">Título</th>
                                                    <th class="px-4 py-2">Descripción</th>
                                                    <th class="px-4 py-2">Estado</th>
                                                    <th class="px-4 py-2">Fecha de Inicio</th>
                                                    <th class="px-4 py-2">Fecha de Finalización</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="task in tasks" :key="task.id">
                                                    <td class="border px-4 py-2">{{ task.title }}</td>
                                                    <td class="border px-4 py-2">{{ task.description }}</td>
                                                    <td class="border px-4 py-2">{{ task.status }}</td>
                                                    <td class="border px-4 py-2">{{ task.start_date }}</td>
                                                    <td class="border px-4 py-2">{{ task.end_date }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
