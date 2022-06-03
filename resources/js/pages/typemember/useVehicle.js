import { reactive, onMounted } from 'vue'
import FormValidationError from '@/utils/formValidationError'

export function useVehicle(form, typeVehicles, initialVehicles = []) {
  const listVehicle = reactive([])

  onMounted(() => {
    initialVehicles.forEach((val) => listVehicle.push(val))
  })

  const vehicleClear = () => {
    listVehicle.splice(0)
  }

  const vehicleOnDelete = (index) => {
    listVehicle.splice(index, 1)
  }

  const vehicleAddValidation = () => {
    if (!form.type_vehicle_id) {
      throw new FormValidationError('Tidak boleh kosong', 'type_vehicle_id')
    }

    if (!form.max_vehicle) {
      throw new FormValidationError('Tidak boleh kosong', 'max_vehicle')
    }

    const listVehicleExist = listVehicle.filter((vehicle) => vehicle.typeVehicleId === form.type_vehicle_id)

    if (listVehicleExist.length) {
      throw new FormValidationError('Jenis Kendaraan sudah ada', 'type_vehicle_id')
    }
  }

  const vehicleOnAdd = () => {
    try {
      form.clearErrors('type_vehicle_id', 'max_vehicle')

      vehicleAddValidation()

      const typeVehicle = typeVehicles.filter((vehicle) => vehicle.value === form.type_vehicle_id)[0]

      listVehicle.push({
        max: form.max_vehicle,
        type: typeVehicle.label,
        typeVehicleId: typeVehicle.value,
      })

      form.reset('type_vehicle_id', 'max_vehicle')
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
