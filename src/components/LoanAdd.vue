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
          <qrcode-stream @init="onInit" @decode="onDecode" v-if="!destroyed" :key="_uid" :track="this.paintOutline">
            <div class="loading-indicator" v-if="loading">
              Loading...
            </div>
          </qrcode-stream>
        </k-column>
      </k-grid>
    </k-view>
  </k-inside>
</template>

<script>
import {QrcodeStream} from 'vue-qrcode-reader'

export default {
  components: {
    QrcodeStream,
  },
  props: {
    item_ids: Array,
    borrower_id: Array,
    start_date: String,
    end_date: String,
  },
  data() {
    return {
      loading: false,
      loan: {
        start_date: this.start_date,
        end_date: this.end_date,
        borrower_id: [],
        item_ids: [],
      },
      hasChanged: false,
      decodedText: ''
    }
  },
  methods: {
    paintOutline(detectedCodes, ctx) {
      for (const detectedCode of detectedCodes) {
        const [firstPoint, ...otherPoints] = detectedCode.cornerPoints

        ctx.strokeStyle = "red";

        ctx.beginPath();
        ctx.moveTo(firstPoint.x, firstPoint.y);
        for (const {x, y} of otherPoints) {
          ctx.lineTo(x, y);
        }
        ctx.lineTo(firstPoint.x, firstPoint.y);
        ctx.closePath();
        ctx.stroke();
      }
    },
    async onInit(promise) {
      this.loading = true

      try {
        await promise
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },
    submit() {
      this.$api.post('/lendmanagement/loan/create', this.loan);
      this.$go('/lendmanagement');
    },
    onDecode(result) {
      this.decodedText = result;
      this.addItem(result);
      console.log(this.loan);
    },
    addItem(result) {
      this.loan.item_ids.push(parseInt(result));
      this.beep();
    },
    beep() {
      let beep = new Audio('https://soundbible.com/mp3/Checkout%20Scanner%20Beep-SoundBible.com-593325210.mp3');
      beep.play();
    },
    input() {
      console.log(this.loan);
    },
  }
};
</script>

<style>
.k-row {
  margin-bottom: 20px;
}
</style>
