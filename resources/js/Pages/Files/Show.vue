<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const props   = defineProps({ file: Object })
const copied  = ref(false)
const starredFile = ref(props.file.starred || false)

async function toggleStar() {
  try {
    const response = await axios.post(`/api/${props.file.id}/star`)
    starredFile.value = response.data.starred
  } catch (error) {
    console.error('Error toggling star:', error)
    alert('Error toggling star: ' + (error.response?.data?.message || error.message))
  }
}

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
