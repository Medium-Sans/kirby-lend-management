<template>
  <k-inside>
    <k-view>

      <k-header>
        {{ $t('view.loan.add') }}

        <k-button-group slot="left">
          <k-button
            :text="$t('lendmanagement.item.add')"
            icon="add"
            @click="$dialog('inventory/item/create')"
          />

          <k-button
            :text="$t('lendmanagement.borrower.add')"
            icon="add"
            @click="$dialog('lendmanagement/borrower/create')"
          />
        </k-button-group>
      </k-header>

      <k-grid>
        <k-column width="1/2">
          <k-form v-model="loan" @input="input" @submit="submit" :fields="{
              startDate: {
                label: $t('lendmanagement.loan.form.startDate'),
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              endDate: {
                label: $t('lendmanagement.loan.form.endDate'),
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              line1: {
                type: 'line'
              },
              borrowerId: {
                label: $t('lendmanagement.borrowers'),
                type: 'multiselect',
                required: true,
                search: true,
                max: 1,
                options: borrowers,
                width: '1'
              },
              line2: {
                type: 'line'
              },
             itemIds: {
                label: $t('lendmanagement.items'),
                type: 'multiselect',
                search: true,
                min: 1,
                required: true,
                options: items,
                width: '1'
             }}">
          </k-form>
        </k-column>

        <k-column width="1/2">
          <StreamBarcodeReader @decode="onDecode" @loaded="onLoaded"></StreamBarcodeReader>
        </k-column>
      </k-grid>
    </k-view>
  </k-inside>
</template>

<script>
import { StreamBarcodeReader } from "vue-barcode-reader";

export default {
  props: {
    items: Array,
    borrowers: Object,
    startDate: String,
    endDate: String,
  },
  data: function() {
    return {
      loan: {
        startDate: this.startDate,
        endDate: this.endDate,
      },
      hasChanged: false
    }
  },
  methods: {
    input() {
      // the data is automatically updated
      this.hasChanged = true;
    },
    submit() {
      // let's send this thing to the server
      this.$api.post('/lendmanagement/loan/create', this.loan);
    },
    onLoaded() {
      console.log("loaded");
    },
    onDecode(result) {
      console.log(result);
    }
  }
};
</script>

<style>
.k-row {
  margin-bottom: 20px;
}
</style>
