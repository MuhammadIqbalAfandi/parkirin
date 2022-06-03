import { ref } from 'vue'

export function useSearchText(initialSearch) {
  const search = ref(initialSearch)

  return {
    search,
  }
}
