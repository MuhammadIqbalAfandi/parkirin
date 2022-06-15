import { reactive, onMounted, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'
import FormValidationError from '@/utils/formValidationError'

export function useVehicle(props, form) {
  const listVehicle = reactive([])

  onMounted(() => {
    if (props.initialVehicles) {
      props.initialVehicles.forEach((val) => listVehicle.push(val))
    }
  })

  watch(
    () => form.type_member_id,
    () => {
      vehicleClear()

      form.reset('plat_number', 'max_vehicle_id')

      Inertia.reload({
        only: ['typeMember'],
        data: { id: form.type_member_id },
      })
    }
  )

  const vehicleClear = () => {
    listVehicle.splice(0)
  }

  const vehicleOnDelete = (index) => {
    listVehicle.splice(index, 1)

    usePage().props.value.errors = {}
  }

  const vehicleAddValidation = () => {
    if (!form.plat_number) {
      throw new FormValidationError(
        'Plat kendaraan tidak boleh kosong',
        'plat_number'
      )
    }

    if (!form.max_vehicle_id) {
      throw new FormValidationError('Tidak boleh kosong', 'max_vehicle_id')
    }

    const platNumberExist = listVehicle.filter(
      (vehicle) => vehicle.platNumber === form.plat_number.toUpperCase()
    )

    if (platNumberExist.length) {
      throw new FormValidationError(
        'Nomor plat kendaraan tidak boleh sama',
        'plat_number'
      )
    }

    const maxVehicles = listVehicle.filter(
      (vehicle) => vehicle.maxVehicleId === form.max_vehicle_id
    )

    if (maxVehicles.length) {
      if (maxVehicles.length + 1 > maxVehicles[0].maxVehicle) {
        throw new FormValidationError(
          'Melibihi batas maksimal kendaraan',
          'plat_number'
        )
      }
    }
  }

  const vehicleOnAdd = () => {
    try {
      form.clearErrors('plat_number', 'max_vehicle_id')

      vehicleAddValidation()

      const typeVehicle = props.typeMember.maxVehicles.filter(
        (maxVehicle) => maxVehicle.value === form.max_vehicle_id
      )[0]

      listVehicle.push({
        platNumber: form.plat_number.toUpperCase(),
        typeVehicle: typeVehicle.label,
        typeVehicleId: typeVehicle.typeVehicleId,
        maxVehicleId: typeVehicle.value,
        maxVehicle: typeVehicle.maxVehicle,
      })

      form.reset('plat_number', 'max_vehicle_id')
    } catch (err) {
      form.setError(err.field, err.message)
    }
  }

  return {
    listVehicle,
    vehicleClear,
    vehicleOnDelete,
    vehicleOnAdd,
  }
}
