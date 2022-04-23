<script setup>
import { computed, watch, reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputText from '@/components/AppInputText.vue'

import { PlatNumberTable } from './TableHeader'

const props = defineProps({
  typeMembers: Array,
  typeVehicles: Array,
  typeMember: Object,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const listPlatNumber = reactive([])

const listPlatNumberClear = () => {
  listPlatNumber.splice(0)
}

const listPlatNumberOnDelete = (index) => {
  listPlatNumber.splice(index, 1)
}

const addPlatNumber = () => {
  form.clearErrors('plat_number')

  if (form.plat_number) {
    if (listPlatNumber.length + 1 > props.typeMember.max) {
      form.setError('plat_number', 'Melibihi batas maksimal kendaraan')

      return
    }

    listPlatNumber.push({ platNumber: form.plat_number, typeVehicleId: form.type_member_id })

    form.reset('plat_number')
  } else {
    form.setError('plat_number', 'Plat kendaraan tidak boleh kosong')
  }
}

const form = useForm({
  name: null,
  phone: null,
  plat_number: null,
  type_vehicle: null,
  type_member_id: null,
})

watch(
  () => form.type_member_id,
  () => {
    listPlatNumberClear()
    form.reset('plat_number')

    Inertia.reload({ only: ['typeMember'], data: { id: form.type_member_id } })
  }
)

const submit = () => {
  form
    .transform((data) => ({
      name: data.name,
      phone: data.phone,
      plat_numbers: listPlatNumber,
      type_member_id: data.type_member_id,
    }))
    .post(route('members.store'), {
      onError: () => {
        Inertia.reload({ only: ['typeMember'], data: { id: form.type_member_id } })
      },
      onSuccess: () => {
        listPlatNumberClear()

        form.reset()
      },
    })
}
</script>

<template>
  <Head title="Tambah Member" />

  <AppLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Tambah Member </template>
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

      <div class="col-12 md:col-4">
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

    <div class="grid">
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
                <AppInputText
                  v-model="form.type_vehicle"
                  label="Jenis Kendaraan"
                  placeholder="jenis kendaraan"
                  :disabled="!form.type_member_id"
                  :error="form.errors.type_vehicle"
                />
              </div>
              <div class="col-12 flex flex-column md:flex-row md:align-items-center justify-content-end mb-3 md:mb-0">
                <Button label="Tambah" class="p-button-outlined" icon="pi pi-car" @click="addPlatNumber" />
              </div>
              <div class="col-12">
                <h1 class="text-base"><i class="pi pi-car"></i> <span class="ml-2">Daftar Plat Kendaraan</span></h1>

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
                      <div class="flex justify-content-end">
                        <Button
                          icon="pi pi-trash"
                          class="p-button-rounded p-button-text"
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
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing || listPlatNumber.length === 0"
                @click="submit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
