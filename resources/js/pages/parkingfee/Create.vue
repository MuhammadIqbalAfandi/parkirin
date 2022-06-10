<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import AppInputNumber from '@/components/AppInputNumber.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

const props = defineProps({
  parkingFees: Object,
})

const disabled = ref(true)

const editButtonLabel = ref('Ubah tarif')

watch(disabled, () => {
  if (disabled.value) {
    editButtonLabel.value = 'Batal ubah tarif'
  } else {
    editButtonLabel.value = 'Ubah tarif'
  }
})

const onEdit = () => {
  disabled.value = !disabled.value
}

const form = useForm({
  id1: props.parkingFees[0]?.id ?? null,
  id2: props.parkingFees[1]?.id ?? null,
  id3: props.parkingFees[2]?.id ?? null,
  id4: props.parkingFees[3]?.id ?? null,
  idNext: props.parkingFees[4]?.id ?? null,
  timePeriod1: props.parkingFees[0]?.timePeriod ?? null,
  timePeriod2: props.parkingFees[1]?.timePeriod ?? null,
  timePeriod3: props.parkingFees[2]?.timePeriod ?? null,
  timePeriod4: props.parkingFees[3]?.timePeriod ?? null,
  timePeriodNext: props.parkingFees[4]?.timePeriod ?? null,
  price1: props.parkingFees[0]?.price ?? null,
  price2: props.parkingFees[1]?.price ?? null,
  price3: props.parkingFees[2]?.price ?? null,
  price4: props.parkingFees[3]?.price ?? null,
  priceNext: props.parkingFees[4]?.price ?? null,
})

useFormErrorReset(form)

const dialogVisible = ref()
const dialogMessage = ref()

const submitValidation = () => {
  const periodTimeCount = [
    form.timePeriod1,
    form.timePeriod2,
    form.timePeriod3,
    form.timePeriod4,
    form.timePeriodNext,
  ].reduce((prev, current) => prev + current)

  if (periodTimeCount > 24) {
    throw new Error('Waktu tidak boleh melebihi 24 jam')
  } else if (periodTimeCount < 24) {
    throw new Error('Waktu tidak boleh kurang dari 24 jam')
  }
}

const onSubmit = () => {
  try {
    submitValidation()

    form
      .transform((data) => ({
        id: [data.id1, data.id2, data.id3, data.id4, data.idNext],
        time_period: [data.timePeriod1, data.timePeriod2, data.timePeriod3, data.timePeriod4, data.timePeriodNext],
        price: [data.price1, data.price2, data.price3, data.price4, data.priceNext],
      }))
      .post(route('parking-fees.store'), { onSuccess: () => (disabled.value = true) })
  } catch (e) {
    dialogVisible.value = true
    dialogMessage.value = e.message
  }
}
</script>

<template>
  <DashboardLayout>
    <Dialog modal header="Ada Kesalahan" :visible="dialogVisible" :closable="false">
      <span style="color: var(--red-500)">{{ dialogMessage }}</span>

      <template #footer>
        <Button label="tutup" @click="dialogVisible = false" />
      </template>
    </Dialog>

    <div class="grid">
      <div class="col-12 md:col-6">
        <Card>
          <template #title>
            <h1>Tarif Parkir <span class="text-base">24 jam pertama</span></h1>

            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                icon="pi pi-pencil"
                class="p-button-outlined p-button-primary"
                :label="editButtonLabel"
                @click="onEdit"
              />
            </div>
          </template>

          <template #content>
            <div class="grid">
              <Divider align="center">
                <span class="p-tag">Periode 1</span>
              </Divider>

              <AppInputNumber
                v-model="form.timePeriod1"
                class="col-12 md:col-6"
                label="Durasi/Jam"
                placeholder="durasi waktu, contoh: 1"
                :disabled="disabled"
                :error="form.errors['time_period.0']"
              />

              <AppInputNumber
                v-model="form.price1"
                class="col-12 md:col-6"
                label="Harga"
                placeholder="contoh: 100000"
                :disabled="disabled"
                :error="form.errors['price.0']"
              />
            </div>

            <Divider align="center">
              <span class="p-tag">Periode 2</span>
            </Divider>

            <div class="grid">
              <AppInputNumber
                v-model="form.timePeriod2"
                class="col-12 md:col-6"
                label="Durasi/Jam"
                placeholder="durasi waktu, contoh: 2"
                :disabled="disabled"
                :error="form.errors['time_period.1']"
              />

              <AppInputNumber
                v-model="form.price2"
                class="col-12 md:col-6"
                label="Harga"
                placeholder="contoh: 100000"
                :disabled="disabled"
                :error="form.errors['price.1']"
              />
            </div>

            <Divider align="center">
              <span class="p-tag">Periode 3</span>
            </Divider>

            <div class="grid">
              <AppInputNumber
                v-model="form.timePeriod3"
                class="col-12 md:col-6"
                label="Durasi/Jam"
                placeholder="durasi waktu, contoh: 3"
                :disabled="disabled"
                :error="form.errors['time_period.2']"
              />

              <AppInputNumber
                v-model="form.price3"
                class="col-12 md:col-6"
                label="Harga"
                placeholder="contoh: 100000"
                :disabled="disabled"
                :error="form.errors['price.2']"
              />
            </div>

            <Divider align="center">
              <span class="p-tag">Periode 4</span>
            </Divider>

            <div class="grid">
              <AppInputNumber
                v-model="form.timePeriod4"
                class="col-12 md:col-6"
                label="Durasi/Jam"
                placeholder="durasi waktu, contoh: 4"
                :disabled="disabled"
                :error="form.errors['time_period.3']"
              />

              <AppInputNumber
                v-model="form.price4"
                class="col-12 md:col-6"
                label="Harga"
                placeholder="contoh: 100000"
                :disabled="disabled"
                :error="form.errors['price.3']"
              />
            </div>

            <Divider align="center">
              <span class="p-tag">Seterusnya</span>
            </Divider>

            <div class="grid">
              <AppInputNumber
                v-model="form.timePeriodNext"
                class="col-12 md:col-6"
                label="Durasi/Jam"
                placeholder="durasi waktu, contoh: 4"
                :disabled="disabled"
                :error="form.errors['time_period.4']"
              />

              <AppInputNumber
                v-model="form.priceNext"
                class="col-12 md:col-6"
                label="Harga"
                placeholder="contoh: 100000"
                :disabled="disabled"
                :error="form.errors['price.4']"
              />
            </div>
          </template>
          <template #footer>
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing || disabled"
                @click="onSubmit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>
