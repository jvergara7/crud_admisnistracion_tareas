<script>
export default {
    name: "Show.vue",
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
            errors: {} // Aquí se almacenarán los mensajes de error de validación
        };
    },
    methods: {

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
                Tarea # {{ task.id }}
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900">Tarea # {{ task.id }}</h3>
                        </div>
                        <div class="px-4 mt-2">
                            <a :href="route('task.index')" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md">
                                Regresar
                            </a>
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <form enctype="multipart/form-data">

                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Título
                                    </label>
                                    <input type="text" disabled v-model="form.title" id="title" name="title" class="form-input w-full rounded-md shadow-sm">
                                </div>
                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Descripción
                                    </label>
                                    <textarea id="description" disabled class="form-input w-full rounded-md shadow-sm" v-model="form.description" rows="2"></textarea>
                                </div>
                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Estado
                                    </label>
                                    <select disabled v-model="form.status" id="status" class="form-select w-full rounded-md shadow-sm">
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
                                        <input disabled type="date" id="start_date" v-model="form.start_date" class="form-input w-full rounded-md shadow-sm">
                                        <p v-if="errors.start_date" class="text-red-500 text-sm">{{ errors.start_date[0] }}</p>
                                    </div>

                                    <div class="flex-1">
                                        <label class="block font-medium text-sm text-gray-700">
                                            Fecha de Finalización
                                        </label>
                                        <input disabled type="date" id="end_date" v-model="form.end_date" class="form-input w-full rounded-md shadow-sm">
                                        <p v-if="errors.end_date" class="text-red-500 text-sm">{{ errors.end_date[0] }}</p>
                                    </div>
                                </div>
                                <div class="w-full mb-4" v-if="files && files.length > 0">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Archivos Actuales
                                    </label>
                                    <table  class="border-collapse w-full">
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
                                                <a :href="`/storage/app/files/${file.file}`" download>
                                                    <button type="button">Descargar</button>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else>
                                    * Esta tarea no cuenta con documentos
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

