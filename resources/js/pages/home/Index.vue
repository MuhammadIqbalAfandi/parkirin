<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppCardStatistic from '@/components/AppCardStatistic.vue'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  cardStatistics: Object,
  barStatistics: Object,
  barHorizontalStatistics: Object,
})

const colors = [
  '#349dcf',
  '#00b2da',
  '#00c7dd',
  '#1fdbdb',
  '#57eed3',
  '#88ffc9',
  '#96ed9a',
  '#a8d96c',
  '#bbc242',
  '#cda91d',
]

const barChart = (chartData) => {
  const colors = ['#349dcf', '#a8d96c']

  const data = {
    datasets: [],
  }

  let id = 0
  for (const key in chartData) {
    data.datasets.push({
      label: key,
      backgroundColor: colors[id],
      data: chartData[key],
    })

    id++
  }

  return data
}

const barChartOption = {
  maintainAspectRatio: false,
  datasetFill: false,
}

const barHorizontalChart = (chartData) => {
  const labels = []
  const data = []

  for (const obj of chartData) {
    labels.push([obj.label1, obj.label2])
    data.push(obj.data)
  }

  return {
    labels: labels,
    datasets: [
      {
        data: data,
        backgroundColor: colors,
      },
    ],
  }
}

const barHorizontalChartOption = {
  maintainAspectRatio: false,
  datasetFill: false,
  indexAxis: 'y',
  plugins: {
    legend: {
      display: false,
    },
  },
}

const pieChart = (chartData) => {
  const labels = []
  const data = []

  for (const key in chartData) {
    labels.push(key)
    data.push(chartData[key])
  }

  return {
    labels: labels,
    datasets: [
      {
        data: data,
        backgroundColor: colors,
      },
    ],
  }
}

const pieChartOption = {
  maintainAspectRatio: false,
  datasetFill: false,
}
</script>

<template>
  <AppLayout>
    <Head title="Dashboard" />

    <div class="grid">
      <div class="col-12 flex flex-wrap justify-content-between card-statistic">
        <div v-for="cardStatistic in cardStatistics" class="flex-grow-1">
          <AppCardStatistic :data="cardStatistic" />
        </div>
      </div>

      <div v-for="barStatistic in barStatistics" class="col-12 md:col-6">
        <Card>
          <template #title>
            <div class="flex flex-column">
              <span>{{ barStatistic.title }}</span>
              <span v-if="barStatistic.description" class="text-base font-normal">{{ barStatistic.description }}</span>
            </div>
          </template>
          <template #content>
            <Chart
              type="bar"
              :width="600"
              :height="300"
              :data="barChart(barStatistic.data)"
              :options="barChartOption"
            />
          </template>
        </Card>
      </div>

      <div v-for="barHorizontalStatistic in barHorizontalStatistics" class="col-12 md:col-6">
        <Card>
          <template #title>
            <div class="flex flex-column">
              <span>{{ barHorizontalStatistic.title }}</span>
              <span v-if="barHorizontalStatistic.description" class="text-base font-normal">{{
                barHorizontalStatistic.description
              }}</span>
            </div>
          </template>
          <template #content>
            <Chart
              type="bar"
              :width="600"
              :height="300"
              :data="barHorizontalChart(barHorizontalStatistic.data)"
              :options="barHorizontalChartOption"
            />
          </template>
        </Card>
      </div>

      <div v-for="pieStatistic in pieStatistics" class="col-12 md:col-6">
        <Card>
          <template #title>
            <div class="flex flex-column">
              <span>{{ pieStatistic.title }}</span>
              <span v-if="pieStatistic.description" class="text-base font-normal">{{ pieStatistic.description }}</span>
            </div>
          </template>
          <template #content>
            <Chart
              type="pie"
              :width="600"
              :height="300"
              :data="pieChart(pieStatistic.data)"
              :options="pieChartOption"
            />
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.card-statistic {
  gap: 1rem;
}
</style>
