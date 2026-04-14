<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props   = defineProps({ file: Object })
const copied  = ref(false)

function copy() {
  navigator.clipboard.writeText(props.file.share_url)
  copied.value = true
  setTimeout(() => copied.value = false, 2000)
}

function destroy() {
  if (confirm('Are you sure you want to delete this file?')) {
    router.delete(route('files.destroy', props.file.id))
  }
}
</script>
