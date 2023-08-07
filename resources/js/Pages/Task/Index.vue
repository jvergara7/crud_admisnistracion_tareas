<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';
import { utcToZonedTime, zonedTimeToUtc } from 'date-fns-tz';
export default {
    components: {
        AppLayout,
    },
    props: {
        completedTasksCount: Number,
        pendingTasksCount: Number,
        tasks: Array,
    },
    mounted() {
        console.log(this.tasks);
    },
    data() {
        return {
            q: ''
        }
    },
    watch: {
        q: function (value) {
            this.$inertia.replace(this.route('task.index', {q: value}))
        }
    },
    methods: {
        getTaskTime(task) {
            const currentDate = new Date();
            const startDate = zonedTimeToUtc(task.start_date, 'America/Bogota');
            const endDate = zonedTimeToUtc(task.end_date, 'America/Bogota');
            const completedDate = zonedTimeToUtc(task.end_date, 'America/Bogota');

            let timeDiff;
            let statusText;

            if (task.status === 'Completada') {
                timeDiff =  currentDate - completedDate;
                statusText = 'Finalizo hace';
            } else if (task.status === 'Por hacer') {
                timeDiff = startDate - currentDate;
                statusText = 'Tiempo para iniciar la tarea';
            } else {
                timeDiff = endDate - currentDate;
                statusText = 'Tiempo para completar la tarea';
            }

            const totalDays = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const totalHours = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);
            const distance = formatDistanceToNow(currentDate, { locale: es, addSuffix: true });

            return `${statusText}: ${totalDays} días y ${totalHours} horas.`;
        },
        viewTask(taskId) {
            this.$inertia.visit(route('task.show', taskId));
        },
        editTask(taskId) {
            this.$inertia.visit(route('task.edit', taskId));
        },
        deleteTaskById(id) {
            this.$inertia.delete(`/task/${id}`);
        },
        async completeTask(taskId) {
            try {
                await this.$inertia.post(route('task.complete', taskId));
                // this.$inertia.visit(route('task.index'));
            } catch (error) {
                console.error(error);
            }
        },
    }
}

</script>
<template>
    <AppLayout title="Task">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tareas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div>
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                                        Tareas
                                    </h2>
                                    <div class="flex space-x-4 mb-4">
                                        <!-- Botón para ir a la creación de una nueva tarea -->
                                        <a :href="route('task.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                            Crear Nueva Tarea
                                        </a>

                                        <!-- Botón para ir al reporte
                                        <a :href="route('report')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                                            Ver Reporte
                                        </a>-->
                                    </div>

                                    <!-- Resto de tu contenido de la vista aquí -->
                                </div>
                                <div class="md:col-span-3 mt-5 md:mt-0">
                                    <div class="shadow bg-white md:rounded-md p-4">
                                        <hr class="my-6">
                                        <div>
                                            <!-- Cards -->
                                            <div class="flex mb-4">
                                                <!-- Tareas Completadas -->
                                                <div class="flex-1 bg-green-200 p-4 rounded-md mr-4">
                                                    <div class="font-bold text-xl mb-2">Tareas Completadas</div>
                                                    <div class="text-2xl">{{ completedTasksCount }}</div>
                                                </div>

                                                <!-- Tareas Por Hacer -->
                                                <div class="flex-1 bg-blue-200 p-4 rounded-md">
                                                    <div class="font-bold text-xl mb-2">Tareas Por Hacer</div>
                                                    <div class="text-2xl">{{ pendingTasksCount }}</div>
                                                </div>
                                            </div>

                                            <!-- Tabla de Tareas -->
                                            <table v-if="tasks && tasks.length > 0" class="w-full table-auto">
                                                <thead>
                                                <tr>
                                                    <th class="px-4 py-2">Título</th>
                                                    <th class="px-4 py-2">Estado</th>
                                                    <th class="px-4 py-2">Tiempo Restante / Tiempo Transcurrido</th>
                                                    <th class="px-4 py-2">Completado</th>
                                                    <th class="px-4 py-2">Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="task in tasks" :key="task.id">
                                                    <td class="border px-4 py-2">{{ task.title }}</td>
                                                    <td class="border px-4 py-2">{{ task.status }}</td>
                                                    <td class="border px-4 py-2">{{ getTaskTime(task) }}</td>
                                                    <td class="border px-4 py-2">
                                                        <input type="checkbox" v-if="task.status !== 'Completada'" @change="completeTask(task.id)">
                                                        <input type="checkbox" v-else disabled checked>
                                                    </td>
                                                    <td class="border px-4 py-2">
                                                        <button @click="viewTask(task.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mr-2">
                                                            Ver
                                                        </button>
                                                        <button @click="editTask(task.id)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full mr-2">
                                                            Editar
                                                        </button>
                                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full mr-2" @click="deleteTaskById(task.id)">Eliminar</button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div v-else>
                                                * Esta tarea no cuenta con documentos
                                            </div>
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


