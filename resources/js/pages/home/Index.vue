<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import { includes } from 'lodash'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  cardStatistics: Array,
  barStatistics: Array,
  barHorizontalStatistics: Array,
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
  <DashboardLayout>
    <Head title="Dashboard" />

    <div class="grid">
      <template v-for="cardStatistic in cardStatistics">
        <div
          v-if="includes(cardStatistic.roleId, $page.props.auth.user.role_id)"
          class="col-12 md:col-4 xl:col-3"
        >
          <Card class="h-full">
            <template #content>
              <div class="flex justify-content-between mb-3">
                <div>
                  <span class="block text-500 font-medium mb-3">{{
                    cardStatistic.title
                  }}</span>
                  <div
                    v-if="cardStatistic.value"
                    class="text-900 font-medium text-xl"
                  >
                    {{ cardStatistic.value }}
                  </div>
                </div>
                <div
                  class="flex align-items-center justify-content-center bg-orange-100 border-round"
                  style="width: 2.5rem; height: 2.5rem"
                >
                  <i
                    class="text-orange-500 text-xl"
                    :class="cardStatistic.icon"
                  ></i>
                </div>
              </div>
              <span class="text-green-500 font-medium"
                >{{ cardStatistic.amount }}
              </span>
              <span class="text-500">
                {{ ' ' + cardStatistic.amountLabel }}</span
              >
            </template>
          </Card>
        </div>
      </template>
    </div>

    <div class="grid">
      <template v-for="barStatistic in barStatistics">
        <div
          v-if="includes(barStatistic.roleId, $page.props.auth.user.role_id)"
          class="col-12 md:col-6"
        >
          <Card>
            <template #title>
              <div class="flex flex-column">
                <span>{{ barStatistic.title }}</span>
                <span
                  v-if="barStatistic.description"
                  class="text-base font-normal"
                  >{{ barStatistic.description }}</span
                >
              </div>
            </template>
            <template v-if="Object.keys(barStatistic.data).length" #content>
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
      </template>

      <!-- <div v-for="barHorizontalStatistic in barHorizontalStatistics" class="col-12 md:col-6">
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
      </div> -->

      <!-- <div v-for="pieStatistic in pieStatistics" class="col-12 md:col-6">
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
      </div> -->
    </div>
  </DashboardLayout>
</template>
