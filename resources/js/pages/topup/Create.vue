<script setup>
import { computed, watch, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppAutocompleteBasic from '@/components/AppAutocompleteBasic.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputText from '@/components/AppInputText.vue'

defineProps({
  members: {
    type: Array,
    default: [],
  },
  typeMembers: Array,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const memberDialogShow = ref(false)

const memberDialogOnHide = () => {
  formMember.reset()

  formMember.clearErrors()

  usePage().props.value.errors = {}

  usePage().props.value.flash.error = null
}

const memberOnComplete = (event) => {
  Inertia.reload({
    data: { search: event.query },
    only: ['members'],
  })
}

const memberOnSelected = (event) => {
  form.member = event.value
}

const formMember = useForm({
  name: null,
  phone: null,
  plat_number: null,
  type_member_id: null,
})

const submitMember = () => {
  formMember.post(route('members.store'), {
    onSuccess: () => {
      formMember.reset()

      memberDialogShow.value = !memberDialogShow.value
    },
  })
}

const form = useForm({
  balance: null,
  member: null,
})

const submit = () => {
  form
    .transform((data) => ({
      balance: data.balance,
      member_id: data.member.id,
    }))
    .post(route('top-ups.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <Head title="Top Up" />

  <AppLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Top Up </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputNumber v-model="form.balance" label="Saldo" placeholder="saldo" :error="form.errors.balance" />
              </div>

              <div class="col-12 md:col-6">
                <AppAutocompleteBasic
                  empty
                  label="Member"
                  field="platNumber"
                  placeholder="member"
                  v-model="form.member"
                  :error="form.errors.member_id"
                  :suggestions="members"
                  @complete="memberOnComplete"
                  @item-select="memberOnSelected"
                >
                  <template #item="slotProps">
                    <template v-if="slotProps.item">
                      <div class="flex flex-column">
                        <span>{{ slotProps.item.name }}</span>
                        <span class="font-bold">{{ slotProps.item.phone }}</span>
                        <span class="font-bold">{{ slotProps.item.platNumber }}</span>
                      </div>
                    </template>
                  </template>

                  <template #empty>
                    <span
                      class="cursor-pointer"
                      style="color: var(--primary-color)"
                      @click="memberDialogShow = !memberDialogShow"
                    >
                      Tambah Member
                    </span>
                  </template>
                </AppAutocompleteBasic>
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing"
                @click="submit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>

    <Dialog
      modal
      v-model:visible="memberDialogShow"
      class="p-fluid"
      header="Tambah Member"
      :style="{ width: '450px' }"
      :breakpoints="{ '960px': '75vw' }"
      @hide="memberDialogOnHide"
    >
      <div class="grid">
        <div class="col-12 md:col-6">
          <AppInputText v-model="formMember.name" label="Nama" placeholder="nama" :error="formMember.errors.name" />
        </div>

        <div class="col-12 md:col-6">
          <AppInputText
            v-model="formMember.phone"
            label="Nomor HP"
            placeholder="nomor hp"
            :error="formMember.errors.phone"
          />
        </div>

        <div class="col-12 md:col-6">
          <AppInputText
            v-model="formMember.plat_number"
            label="Plat Kendaraan"
            placeholder="plat kendaraan"
            :error="formMember.errors.plat_number"
          />
        </div>

        <div class="col-12 md:col-6">
          <AppDropdown
            label="Jenis member"
            placeholder="pilih satu"
            v-model="formMember.type_member_id"
            :options="typeMembers"
            :error="formMember.errors.type_member_id"
          />
        </div>
      </div>

      <template #footer>
        <div class="flex flex-column md:flex-row justify-content-end">
          <Button
            label="Simpan"
            icon="pi pi-check"
            class="p-button-outlined"
            :disabled="formMember.processing"
            @click="submitMember"
          />
        </div>
      </template>
    </Dialog>
  </AppLayout>
</template>
