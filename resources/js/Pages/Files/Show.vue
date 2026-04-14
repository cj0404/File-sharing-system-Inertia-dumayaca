<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const props   = defineProps({ file: Object })
const copied  = ref(false)
const favoritedFile = ref(props.file.starred || false)

async function toggleFavorite() {
  try {
    const response = await axios.post(`/api/files/${props.file.id}/favorite`)
    favoritedFile.value = response.data.starred
  } catch (error) {
    console.error('Error toggling favorite:', error)
    alert('Error toggling favorite: ' + (error.response?.data?.message || error.message))
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
