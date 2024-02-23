<template>
    <div x-show="isOpen" x-cloak class="fixed inset-0 overflow-y-auto z-50 flex justify-center items-center">
        <div class="fixed inset-0 bg-black opacity-50"></div>

        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md">
            <h2 class="text-lg font-semibold mb-4">Confirm Deletion</h2>
            <p class="mb-6">Are you sure you want to delete this sample set?</p>

            <div class="flex justify-end">
                <button @click="cancel" class="mr-4 text-gray-600 hover:text-gray-800">Cancel</button>
                <button @click="confirmDelete"
                    class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Delete</button>
            </div>
        </div>
    </div>
</template>
