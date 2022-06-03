import { onMounted, watch, ref } from 'vue'
import dayjs from 'dayjs'

export function useDateRangeFilter(initialDateRage = []) {
  const dates = ref(null)
  const startDate = ref(null)
  const endDate = ref(null)

  onMounted(() => {
    if (initialDateRage[0] || initialDateRage[1]) {
      if (initialDateRage[1]) {
        dates.push([new Date(initialDateRage[0]), new Date(initialDateRage[1])])
      } else {
        dates.push([new Date(props.initialDateRage[1]), null])
      }
    }
  })

  watch(dates, () => {
    if (dates.value[1]) {
      startDate.value = dayjs(dates.value[0]).format('YYYY-MM-DD')
      endDate.value = dayjs(dates.value[1]).format('YYYY-MM-DD')
    } else if (dates.value[0]) {
      startDate.value = dayjs(dates.value[0]).format('YYYY-MM-DD')
      endDate.value = null
    }
  })

  return {
    dates,
    startDate,
    endDate,
  }
}
