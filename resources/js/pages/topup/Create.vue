<script setup>
import { computed, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
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

const submit = () => {
  form.post(route('top-ups.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <Head title="Top Up" />

  <AppLayout>
    <template v-if="form.member">
      <h1 class="text-2xl font-bold">Detail Member</h1>

      <div class="grid px-2">
        <div class="col-auto mr-7">
          <h2>
            <span class="text-base"> <i class="pi pi-user" /> Nama</span>

            <br />

            <span class="text-lg">{{ form.member.name }}</span>
          </h2>
        </div>

        <div class="col-auto mr-7">
          <h2>
            <span class="text-base"> <i class="pi pi-mobile" /> No HP</span>

            <br />

            <span class="text-lg">{{ form.member.phone }}</span>
          </h2>
        </div>
      </div>

      <div class="grid">
        <div class="col-12">
          <h2 class="mb-4">
            <span class="text-base">
              <i class="pi pi-car red-700" />
              Detail Plat Kendaraan
            </span>

            <br />

            <span class="text-lg">{{ form.member.platNumber }}</span>
          </h2>
        </div>
      </div>
    </template>

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
                  placeholder="member"
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
  </AppLayout>
</template>
