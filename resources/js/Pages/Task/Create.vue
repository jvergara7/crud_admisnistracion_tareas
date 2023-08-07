<script>
export default {
    name: 'ProjectsCreate',
    data() {
        return {
            form: {
                title: '',
                description: '',
                status: 'Por hacer',
                start_date: new Date(),
                end_date: '',
                files: []
            },
            errors: {},
        }
    },
    methods: {
        async submitForm() {
            try {
                const response = await this.$inertia.post(route('task.store'), this.form);
                this.$inertia.visit(response.headers.location);
            } catch (error) {
                if (error.response && error.response.data) {
                    this.errors = error.response.data.errors;
                    console.log(this.errors);
                }
            }
        },
        onFileChange(event) {
            this.form.files = event.target.files;
        },
    }
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="CreateTask">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear nueva tarea
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900">Crear Tarea</h3>
                        </div>
                        <div class="px-4 mt-2">
                            <a :href="route('task.index')" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md">
                                Regresar
                            </a>
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <form @submit.prevent="submitForm" enctype="multipart/form-data">

                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Title
                                    </label>
                                    <input type="text" v-model="form.title" class="form-input w-full rounded-md shadow-sm" required>
                                    <p v-if="errors && errors.title" class="text-red-500 text-sm">{{ errors.title[0] }}</p>
                                </div>

                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Descripción
                                    </label>
                                    <textarea
                                        class="form-input w-full rounded-md shadow-sm"
                                        v-model="form.description"
                                        rows="2"
                                        required
                                    ></textarea>
                                    <p v-if="errors.description" class="text-red-500 text-sm">{{ errors.description[0] }}</p>
                                </div>
                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Estado
                                    </label>
                                    <select v-model="form.status" class="form-select w-full rounded-md shadow-sm">
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
                                        <input required type="date" v-model="form.start_date" class="form-input w-full rounded-md shadow-sm">
                                        <p v-if="errors.start_date" class="text-red-500 text-sm">{{ errors.start_date[0] }}</p>
                                    </div>

                                    <div class="flex-1">
                                        <label class="block font-medium text-sm text-gray-700">
                                            Fecha de Finalización
                                        </label>
                                        <input type="date" required v-model="form.end_date" class="form-input w-full rounded-md shadow-sm">
                                        <p v-if="errors.end_date" class="text-red-500 text-sm">{{ errors.end_date[0] }}</p>
                                    </div>
                                </div>

                                <div class="w-full mb-4">
                                    <label class="block font-medium text-sm text-gray-700">
                                        Archivos
                                    </label>
                                    <input type="file" ref="fileInput" class="form-input w-full rounded-md shadow-sm" @change="onFileChange" multiple>
                                    <p v-if="errors.files" class="text-red-500 text-sm">{{ errors.files[0] }}</p>
                                </div>

                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                    Guardar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
