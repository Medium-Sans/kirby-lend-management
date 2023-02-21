<template>
  <k-inside>
    <k-view class="k-page-view">

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

      <k-grid gutter="large">
        <k-column width="1/2">
          <k-fieldset v-model="loan" @input="input" :fields="{
              start_date: {
                label: $t('lendmanagement.loan.form.startDate'),
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              end_date: {
                label: $t('lendmanagement.loan.form.endDate'),
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              line1: {
                type: 'line'
              },
              borrower_id: {
                label: $t('lendmanagement.borrower'),
                type: 'multiselect',
                required: true,
                search: true,
                max: 1,
                options: borrower_id,
                width: '1'
              },
              line2: {
                type: 'line'
              },
              item_ids: {
                label: $t('lendmanagement.items'),
                type: 'multiselect',
                search: true,
                min: 1,
                required: true,
                options: item_ids,
                width: '1'
              },
              line3: {
                type: 'line'
              },
             }">
          </k-fieldset>
          <k-button icon="check" @click="submit">Save</k-button>
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
  components: {
    StreamBarcodeReader,
  },
  props: {
    item_ids: Array,
    borrower_id: Array,
    start_date: String,
    end_date: String,
  },
  data() {
    return {
        loan: {
          start_date: this.start_date,
          end_date: this.end_date,
          borrower_id: [],
          item_ids: [],
        },
        hasChanged: false,
        decodedText: '',
    }
  },
  methods: {
    input() {
      console.log(this.loan);
    },
    submit() {
      // let's send this thing to the server
      this.$api.post('/lendmanagement/loan/create', this.loan);
    },
    onLoaded() {
    },
    onDecode(result) {
      this.decodedText = result.text;
      console.log(this.decodedText);
    }
  }
};
</script>

<style>
.k-row {
  margin-bottom: 20px;
}
</style>
