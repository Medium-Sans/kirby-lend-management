<template>
  <k-inside>
    <k-view class="k-page-view">

      <k-header>
        {{ $t('view.lend.add') }}

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
          <k-fieldset v-model="lend" @input="input" :fields="{
              start_date: {
                label: $t('lendmanagement.lend.form.startDate'),
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              end_date: {
                label: $t('lendmanagement.lend.form.endDate'),
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
              }
             }">
          </k-fieldset>

          <section class="k-itemstable-section">
            <vue-good-table
              v-if="rows.length && !isLoading"
              ref="table"
              :columns="columns"
              :rows="rows"
            >
              <template slot="table-row" slot-scope="props">
                <span
                  class="k-quantity-buttons"
                  v-if="props.column.field === 'p-add'"
                  @click="removeQuantity(props.row.id)"
                >
                  <k-icon
                    class="block"
                    type="remove"/>
                </span>

                <span
                  class="k-quantity-buttons"
                  v-if="props.column.field === 'p-remove'"
                  @click="addQuantity(props.row.id)"
                >
                  <k-icon
                    class="block"
                    type="add" />
                </span>
              </template>
            </vue-good-table>
          </section>

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
import {QrcodeStream} from "vue-qrcode-reader";
import {VueGoodTable} from "vue-good-table";

export default {
  components: {
    VueGoodTable,
    QrcodeStream
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
      lend: {
        start_date: this.start_date,
        end_date: this.end_date,
        borrower_id: [],
        item_ids: [],
      },
      hasChanged: false,
      decodedText: '',
      columns: [
        {
          label: 'Item',
          field: 'name',
          type: 'text',
          tdClass: 'vgt-left-align',
        },
        {
          label: 'Quantity',
          field: 'qty',
          type: 'number',
          tdClass: 'vgt-center-align',
          width: '90px'
        },
        {
          label: '',
          field: 'p-add',
          tdClass: 'vgt-center-align',
          width: '50px'
        },
        {
          label: '',
          field: 'p-remove',
          tdClass: 'vgt-center-align',
          width: '50px'
        },
      ],
      rows: [
        { id: 1, name: "John", qty: 20 },
        { id: 2, name: "John", qty: 20 },
      ],
    };
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
    }
    ,
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
      this.$api.post('/lendmanagement/lend/create', this.lend);
      this.$go('/lendmanagement');
    },
    onDecode(result) {
      this.decodedText = result;
      this.addItem(result);
      this.beep();
    },
    addItem(result) {
      this.lend.item_ids.push(parseInt(result));
      let item = this.$api.get('/lendmanagement/item/' + result);
      console.log(item);
      this.rows.push({ id: item.id, name: item.name, qty: item.qty});
    },
    beep() {
      let beep = new Audio('https://soundbible.com/mp3/Checkout%20Scanner%20Beep-SoundBible.com-593325210.mp3');
      beep.play();
    },
    input(result) {
      console.log(result);
    },
    addQuantity(id) {
      this.rows.forEach(function (value) {
        if (value.id === id) {
          value.qty++;
        }
      });
    },
    removeQuantity(id) {
      this.rows.forEach(function (value) {
        if (value.id === id) {
          value.qty--;
        }
      });
    }
  }
};
</script>

<style lang="scss">
@import "../assets/css/styles.scss";
</style>
