<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { groupBy } from 'lodash'
import { useConfirm } from 'primevue/useconfirm'
import { platNumberTable } from './tableHeader'
import { useVehicle } from './useVehicle'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import FormValidationError from '@/utils/formValidationError'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputText from '@/components/AppInputText.vue'

const props = defineProps({
  member: Object,
  typeMembers: Array,
  typeMember: Object,
  initialVehicles: Object,
})

const form = useForm({
  name: props.member.name,
  phone: props.member.phone,
  plat_number: null,
  max_vehicle_id: null,
  type_member_id: props.member.typeMemberId,
})

useFormErrorReset(form)

const { listVehicle, vehicleOnDelete, vehicleOnAdd } = useVehicle(props, form)

const confirm = useConfirm()

const errorSubmitVisible = ref(false)

const errorSubmitText = ref()

const submitValidation = () => {
  const listPlatNumberGroup = groupBy(listVehicle, 'typeVehicleId')
  const listVehicles = []

  for (let key in listPlatNumberGroup) {
    listVehicles.push({
      typeVehicleId: listPlatNumberGroup[key][0]['typeVehicleId'],
      maxVehicle: listPlatNumberGroup[key][0]['maxVehicle'],
      count: listPlatNumberGroup[key].length,
    })
  }

  listVehicles.forEach((val) => {
    if (val.count > val.maxVehicle) {
      throw new FormValidationError('Ada perubahan pada batas maksimal kendaraan')
    }
  })
}

const formSent = () => {
  form
    .transform((data) => ({
      name: data.name,
      phone: data.phone,
      vehicles: listVehicle,
      type_member_id: data.type_member_id,
    }))
    .put(route('members.update', props.member.id))
}

const onSubmit = () => {
  try {
    submitValidation()

    if (props.member.typeMemberId != form.type_member_id) {
      confirm.require({
        message: `Dikenakan tagihan perubahan member sebesar ${props.typeMember.price}`,
        header: 'Tagihan Perubahan Jenis Member',
        acceptLabel: 'Bayar dan simpan',
        rejectLabel: 'Batalkan',
        accept: () => {
          formSent()
        },
        reject: () => {
          confirm.close()
        },
      })
    } else {
      formSent()
    }
  } catch (err) {
    errorSubmitVisible.value = true
    errorSubmitText.value = err.message
  }
}
</script>

<template>
  <Head title="Ubah Member" />

  <DashboardLayout>
    <ConfirmDialog></ConfirmDialog>

    <Dialog
      modal
      header="Error"
      v-model:visible="errorSubmitVisible"
      contentStyle="color: #b71c1c"
      :closable="false"
      :style="{ width: '40vw' }"
    >
      <p>{{ errorSubmitText }}</p>
    </Dialog>

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
                    @click="vehicleOnAdd"
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
                  :value="listVehicle"
                >
                  <Column
                    v-for="value in platNumberTable"
                    :field="value.field"
                    :header="value.header"
                    :key="value.field"
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
                          @click="vehicleOnDelete(index)"
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
                  :disabled="form.processing || listVehicle.length === 0"
                  @click="onSubmit"
                />
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>
