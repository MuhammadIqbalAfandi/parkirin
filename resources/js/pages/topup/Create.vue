<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import { useConfirm } from 'primevue/useconfirm'
import AppAutocompleteBasic from '@/components/AppAutocompleteBasic.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  members: {
    type: Array,
    default: [],
  },
})

const memberOnComplete = (event) => {
  Inertia.reload({
    data: { search: event.query },
    only: ['members'],
  })
}

const memberOnSelected = (event) => {
  form.member = event.value
}

const gotoMember = () => {
  return Inertia.get(route('members.create'))
}

const checkMember = () => {
  return form.member === null || typeof form.member !== 'object'
}

const form = useForm({
  member: null,
})

useFormErrorReset(form)

const confirm = useConfirm()

const formSent = () => {
  form
    .transform((data) => ({
      member_id: data.member.id,
    }))
    .post(route('top-ups.store'), { onSuccess: () => form.reset() })
}

const onSubmit = () => {
  confirm.require({
    message: `Dikenakan tagihan sebesar ${form.member.price}`,
    header: 'Top Up',
    acceptLabel: 'Bayar dan simpan',
    rejectLabel: 'Batalkan',
    accept: () => {
      formSent()
    },
    reject: () => {
      confirm.close()
    },
  })
}
</script>

<template>
  <Head title="Top Up" />

  <DashboardLayout>
    <ConfirmDialog></ConfirmDialog>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title>Detail Member</template>
          <template v-if="form.member" #content>
            <div v-if="form.member.id" class="grid">
              <div class="col-12">
                <h2>
                  <span class="text-base"> <i class="pi pi-user" /> Nama</span>

                  <br />

                  <span class="text-lg">{{ form.member.name }}</span>
                </h2>
              </div>

              <div class="col-12">
                <h2>
                  <span class="text-base"> <i class="pi pi-id-card" /> Jenis Member</span>

                  <br />

                  <span class="text-lg">{{ form.member.type }}</span>
                </h2>
              </div>

              <div class="col-12">
                <h2>
                  <span class="text-base"> <i class="pi pi-wallet" /> Tarif Member</span>

                  <br />

                  <span class="text-lg">{{ form.member.price }}</span>
                </h2>
              </div>

              <div class="col-12">
                <h2>
                  <span class="text-base"> <i class="pi pi-calendar-times" /> Berakhir</span>

                  <br />

                  <span class="text-lg">{{ form.member.expDate }}</span>
                </h2>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Top Up </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppAutocompleteBasic
                  label="Member"
                  field="name"
                  placeholder="cari, contoh: tina"
                  v-model="form.member"
                  :error="form.errors.member"
                  :suggestions="members"
                  @complete="memberOnComplete"
                  @item-select="memberOnSelected"
                >
                  <template #item="slotProps">
                    <template v-if="slotProps.item">
                      <div class="flex flex-column">
                        <span>{{ slotProps.item.name }}</span>
                        <span>{{ slotProps.item.type }}</span>
                        <span class="font-bold">{{ slotProps.item.platNumber }}</span>
                      </div>
                    </template>
                  </template>

                  <template #empty>
                    <span class="cursor-pointer" style="color: var(--primary-color)" @click="gotoMember">
                      Buat Member
                    </span>
                  </template>
                </AppAutocompleteBasic>
              </div>
            </div>
          </template>
          <template #footer>
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Topup"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing || checkMember()"
                @click="onSubmit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>
