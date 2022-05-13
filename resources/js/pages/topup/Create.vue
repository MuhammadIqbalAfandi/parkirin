<script setup>
import { computed, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import { useConfirm } from 'primevue/useconfirm'
import AppAutocompleteBasic from '@/components/AppAutocompleteBasic.vue'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  members: {
    type: Array,
    default: [],
  },
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
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

const form = useForm({
  member: null,
})

const confirm = useConfirm()

const formSent = () => {
  form
    .transform((data) => ({
      member_id: data.member.id,
    }))
    .post(route('top-ups.store'), { onSuccess: () => form.reset() })
}

const submit = () => {
  confirm.require({
    message: `Dikenakan tagihan sebesar ${form.member.price}`,
    header: 'Top Up',
    acceptLabel: 'Bayar dan simpan',
    rejectLabel: 'Batalkan',
    accept: () => {
      formSent()
    },
    reject: () => {
      console.info('transaksi dibatalkan')
    },
  })
}
</script>

<template>
  <Head title="Top Up" />

  <AppLayout>
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
                  <span class="text-base"> <i class="pi pi-phone" /> Nomor HP</span>

                  <br />

                  <span class="text-lg">{{ form.member.phone }}</span>
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
                  empty
                  label="Member"
                  field="name"
                  placeholder="cari, contoh: 08xx, tina"
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
                        <span class="font-bold">{{ slotProps.item.phone }}</span>
                        <span class="font-bold">{{ slotProps.item.type }}</span>
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
                :disabled="form.processing"
                @click="submit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
