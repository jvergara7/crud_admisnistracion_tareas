<script>
import axios from 'axios';
export default {
    name: "Edit.vue",
    props: {
        task: Object,
        files: Object
    },
    data() {
        return {
            form: {
                title: this.task.title,
                description: this.task.description,
                status: this.task.status,
                end_date: this.task.end_date,
                start_date: this.task.start_date,
            },
            errors: {}
        };
    },
    methods: {
        async updateTask() {
            try {
                const taskData = JSON.parse(JSON.stringify({
                    title: this.form.title,
                    description: this.form.description,
                    status: this.form.status,
                    end_date: this.form.end_date,
                    start_date: this.form.start_date,
                }));
                console.log(taskData)
                await this.$inertia.put(route('task.update', this.task.id), taskData);
            } catch (error) {
                this.errors = error.response.data.errors;
                console.log(this.errors);
            }
        },
        async deleteFile(fileId) {
            try {
                await this.$inertia.delete(route('task.deleteFile', fileId));
                // Actualiza la lista de archivos en el componente después de eliminar
                this.task.files = this.task.files.filter(file => file.id !== fileId);
                this.$inertia.visit(route('task.edit', this.task.id));
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="CreateEdit">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Tarea
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900">Editar tarea</h3>
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <form @submit.prevent="updateTask" enctype="multipart/form-data">

                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Título
                                    </label>
                                    <input type="text" v-model="form.title" id="title" name="title" class="form-input w-full rounded-md shadow-sm">
                                </div>
                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Descripción
                                    </label>
                                    <textarea id="description" class="form-input w-full rounded-md shadow-sm" v-model="form.description" rows="2"></textarea>
                                </div>
                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Estado
                                    </label>
                                    <select v-model="form.status" id="status" class="form-select w-full rounded-md shadow-sm">
                                        <option value="Por hacer">Por hacer</option>
                                        <option value="En progreso">En progreso</option>
                                        <option value="Completada">Completada</option>
                                    </select>
                                    <p v-if="errors.status" class="text-red-500 text-sm">{{ errors.status[0] }}</p>
                                </div>
                                <div class="w-full flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block font-medium text-sm text-gray-700">
                                            Fecha de Inicio
                                        </label>
                                        <input type="date" id="start_date" v-model="form.start_date" class="form-input w-full rounded-md shadow-sm">
                                        <p v-if="errors.start_date" class="text-red-500 text-sm">{{ errors.start_date[0] }}</p>
                                    </div>

                                    <div class="flex-1">
                                        <label class="block font-medium text-sm text-gray-700">
                                            Fecha de Finalización
                                        </label>
                                        <input type="date" id="end_date" v-model="form.end_date" class="form-input w-full rounded-md shadow-sm">
                                        <p v-if="errors.end_date" class="text-red-500 text-sm">{{ errors.end_date[0] }}</p>
                                    </div>
                                </div>
                                <div v-if="files && files.length > 0" class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Archivos Actuales
                                    </label>
                                    <table class="border-collapse w-full">
                                        <thead>
                                        <tr>
                                            <th class="border border-gray-400 px-4 py-2">Nombre del Archivo</th>
                                            <th class="border border-gray-400 px-4 py-2">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="file in files" :key="file.id">
                                            <td class="border border-gray-400 px-4 py-2">
                                                <span v-if="file.file.length > 20" v-bind:title="file.file">{{ file.file.substring(0, 17) }}...</span>
                                                <span v-else>{{ file.file }}</span>
                                            </td>
                                            <td class="border border-gray-400 px-4 py-2">
                                                <button class="w-16 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-md mr-2" @click="deleteFile(file.id)">Borrar</button>
                                                <a :href="route('download', { fileId: file.id })" class="w-16 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-md mr-2">Descargar</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else>
                                    * Esta tarea no cuenta con documentos
                                </div>

                                <div class="flex mt-4">
                                    <button type="submit" class="w-24 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded-full mr-3">
                                        Guardar
                                    </button>

                                    <a :href="route('task.index')" class="w-24 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded-full ml-3">
                                        Cancelar
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
