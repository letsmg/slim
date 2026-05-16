<template>
    <footer class="bg-gray-900 border-t border-gray-700 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400">
                    &copy; {{ currentYear }} Slim App. Todos os direitos reservados.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">Privacidade</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">Termos</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">Suporte</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue'

const currentYear = computed(() => new Date().getFullYear())
</script>