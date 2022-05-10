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
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card class="surface-200">
          <template #title>Detail Member</template>
          <template v-if="form.member" #content>
            <h3 class="text-base font-bold mb-0">Nama</h3>
            <p>{{ form.member.name }}</p>

            <h3 class="text-base font-bold mb-0">Nomor HP</h3>
            <p>{{ form.member.phone }}</p>

            <h3 class="text-base font-bold mb-0">Plat Kendaraan</h3>
            <p>
              {{ form.member.platNumber }}
            </p>

            <h3 class="text-base font-bold mb-0">Jenis Member</h3>
            <p>{{ form.member.type }}</p>

            <h3 class="text-base font-bold mb-0">Berakhir</h3>
            <p>{{ form.member.expDate }}</p>
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

            <Divider type="dashed" />
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
