<script setup>
import { computed, watch, ref, reactive, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppDialog from '@/components/AppDialog.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputText from '@/components/AppInputText.vue'

import { PlatNumberTable } from './TableHeader'

const props = defineProps({
  member: Object,
  typeMembers: Array,
  typeMember: Object,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

onMounted(() => {
  props.member.vehicles.forEach((val) => listPlatNumber.push(val))
})

const listPlatNumber = reactive([])

const listPlatNumberClear = () => {
  listPlatNumber.splice(0)
}

const listPlatNumberOnDelete = (index) => {
  listPlatNumber.splice(index, 1)

  usePage().props.value.errors = {}
}

const addPlatNumber = () => {
  form.clearErrors('plat_number', 'max_vehicle_id')

  if (!form.plat_number) {
    form.setError('plat_number', 'Plat kendaraan tidak boleh kosong')
    return
  }

  if (!form.max_vehicle_id) {
    form.setError('max_vehicle_id', 'Tidak boleh kosong')
    return
  }

  const listPlatNumberExist = listPlatNumber.filter((val) => val.platNumber === form.plat_number.toUpperCase())
  if (listPlatNumberExist.length) {
    form.setError('plat_number', 'Nomor plat kendaraan tidak boleh sama')
    return
  }

  const maxVehicles = listPlatNumber.filter((val) => val.maxVehicleId === form.max_vehicle_id)
  if (maxVehicles.length) {
    if (maxVehicles.length + 1 > maxVehicles[0].maxVehicle) {
      form.setError('plat_number', 'Melibihi batas maksimal kendaraan')
      return
    }
  }

  const typeVehicle = props.typeMember.maxVehicles.filter((val) => val.value === form.max_vehicle_id)[0]
  listPlatNumber.push({
    platNumber: form.plat_number.toUpperCase(),
    typeVehicle: typeVehicle.label,
    typeVehicleId: typeVehicle.typeVehicleId,
    maxVehicleId: typeVehicle.value,
    maxVehicle: typeVehicle.maxVehicle,
  })

  form.reset('plat_number', 'max_vehicle_id')
}

const visibleDialog = ref(false)

const confirmDialog = () => {
  visibleDialog.value = true
}

const onAgree = () => Inertia.delete(route('members.destroy', props.member.id))

const onCancel = () => (visibleDialog.value = false)

const form = useForm({
  name: props.member.name,
  phone: props.member.phone,
  plat_number: null,
  max_vehicle_id: null,
  type_member_id: props.member.typeMemberId,
})

watch(
  () => form.type_member_id,
  () => {
    listPlatNumberClear()
    form.reset('plat_number', 'max_vehicle_id')

    Inertia.reload({ only: ['typeMember'], data: { id: form.type_member_id } })
  }
)

const submit = () => {
  form
    .transform((data) => ({
      name: data.name,
      phone: data.phone,
      vehicles: listPlatNumber,
      type_member_id: data.type_member_id,
    }))
    .put(route('members.update', props.member.id))
}
</script>

<template>
  <Head title="Ubah Member" />

  <AppLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Ubah Member </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText v-model="form.name" label="Nama" placeholder="nama" :error="form.errors.name" />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText v-model="form.phone" label="Nomor HP" placeholder="nomor hp" :error="form.errors.phone" />
              </div>

              <div class="col-12 md:col-6">
                <AppDropdown
                  label="Jenis member"
                  placeholder="pilih satu"
                  v-model="form.type_member_id"
                  :options="typeMembers"
                  :error="form.errors.type_member_id"
                />
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card class="bg-primary">
          <template #title>Detail Harga</template>
          <template v-if="typeMember" #content>
            <h3 class="text-base font-bold mb-0">Jenis Member</h3>
            <p>{{ typeMember.type }}</p>

            <h3 class="text-base font-bold mb-0">Batas Maksimal Kendaraan</h3>
            <p>{{ typeMember.max }}</p>

            <h3 class="text-base font-bold mb-0">Keterangan</h3>
            <p>
              {{ typeMember.description }}
            </p>

            <h3 class="text-base font-bold mb-0">Tarif Member</h3>
            <p>{{ typeMember.price }}</p>
          </template>
        </Card>
      </div>
    </div>

    <div v-if="typeMember" class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText
                  v-model="form.plat_number"
                  label="Plat Kendaraan"
                  placeholder="plat kendaraan"
                  :disabled="!form.type_member_id"
                  :error="form.errors.plat_number"
                />
              </div>
              <div class="col-12 md:col-6">
                <AppDropdown
                  v-model="form.max_vehicle_id"
                  label="Jenis Kendaraan"
                  placeholder="jenis kendaraan"
                  :disabled="!form.type_member_id"
                  :options="typeMember.maxVehicles"
                  :error="form.errors.max_vehicle_id"
                />
              </div>
              <div class="col-12 mb-3 md:mb-0">
                <div class="flex flex-column md:flex-row md:align-items-center justify-content-end">
                  <Button
                    label="Tambah"
                    class="p-button-outlined"
                    icon="pi pi-car"
                    :disabled="!form.type_member_id"
                    @click="addPlatNumber"
                  />
                </div>
              </div>
              <div class="col-12">
                <DataTable
                  striped-rows
                  row-hover
                  responsive-layout="scroll"
                  column-resize-mode="expand"
                  edit-mode="cell"
                  :value="listPlatNumber"
                >
                  <Column
                    v-for="platNumberTable in PlatNumberTable"
                    :field="platNumberTable.field"
                    :header="platNumberTable.header"
                    :key="platNumberTable.field"
                  />

                  <Column>
                    <template #body="{ index }">
                      <span style="color: #b71c1c">{{ $page.props.errors[`vehicles.${index}.platNumber`] }}</span>
                    </template>
                  </Column>

                  <Column>
                    <template #body="{ index }">
                      <div class="flex justify-content-end">
                        <Button
                          icon="pi pi-trash"
                          class="p-button-rounded p-button-text"
                          :class="{ 'p-button-danger': $page.props.errors[`vehicles.${index}.platNumber`] }"
                          @click="listPlatNumberOnDelete(index)"
                        />
                      </div>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </template>
          <template #footer>
            <div class="grid">
              <div class="col-12 flex flex-column md:flex-row justify-content-center md:justify-content-end">
                <Button
                  label="Simpan"
                  icon="pi pi-check"
                  class="p-button-outlined"
                  :disabled="form.processing"
                  @click="submit"
                />
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
