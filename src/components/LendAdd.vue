<template>
  <k-inside>

    <k-header>
      {{ $t('view.lend.add') }}

      <k-button-group slot="buttons">
        <k-button
          :text="$t('lendmanagement.item.add')"
          icon="add"
          variant="filled"
          @click="$dialog('lendmanagement/item/create')"
        />
        <k-button
          :text="$t('lendmanagement.borrower.add')"
          icon="add"
          variant="filled"
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
                after: $t('lendmanagement.lendAdd.search'),
                max: 1,
                options: borrower_id,
                width: '1'
              },
              line2: {
                type: 'line'
              },
             }">
        </k-fieldset>

        <k-multiselect-field
          style="margin-bottom: var(--spacing-4);"
          :label="$t('lendmanagement.items')"
          :after="$t('lendmanagement.lendAdd.search')"
          :options="items"
          required="true"
          @input="itemsChange($event)"
        />

        <table class="k-lend-items">

          <tr>
            <th class="k-lend-items-options" style="text-align: center;">#</th>
            <th>{{ $t('lendmanagement.inventory.table.title') }}</th>
            <th colspan="2">{{ $t('lendmanagement.inventory.table.quantity') }}</th>
            <th class="k-lend-items-options"></th>
          </tr>

          <tr v-for="(item, id) in lend.items" :key="id">
            <td style="text-align: center;">{{ id + 1 }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.qty }}</td>
            <td>
              <k-button-group layout="collapsed">
                <k-button variant="filled"
                          icon="add"
                          @click="addQuantity(item.id)"
                />
                <k-button variant="filled"
                          icon="remove"
                          @click="removeQuantity(item.id)"
                />
              </k-button-group>
            </td>
            <td class="k-lend-items-options">
              <k-button icon="trash" @click="removeItem(item.id)"/>
            </td>
          </tr>
        </table>

        <k-line-field></k-line-field>

        <k-button variant="filled" icon="check" @click="submit">
          {{ $t('lendmanagement.lendAdd.save') }}
        </k-button>
      </k-column>

      <k-column width="1/2">
        <qrcode-stream @init="onInit" @decode="onDecode" v-if="!destroyed" :key="_uid" :track="this.paintOutline">
          <div class="loading-indicator" v-if="loading">
            Loading...
          </div>
        </qrcode-stream>
      </k-column>

      <k-dialog
        ref="dialog"
        submitButton="Ok"
        theme="positive"
        icon="check"
      >
        <k-text>
          {{ message }}
        </k-text>
      </k-dialog>

    </k-grid>
  </k-inside>
</template>

<script>
import {QrcodeStream} from "vue-qrcode-reader";

export default {
  computed: {
    structure() {
      return structure
    }
  },
  components: {
    QrcodeStream
  },
  props: {
    items: Array,
    borrower_id: Array,
    lend_items: Array,
    start_date: String,
    end_date: String,
    message: String,
  },
  data() {
    return {
      loading: false,
      lend: {
        start_date: this.start_date,
        end_date: this.end_date,
        borrower_id: [],
        items: [],
        item_ids: [],
      },
      hasChanged: false,
      decodedText: '',
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
    log(event) {
      console.log(event);
    },
    submit() {
      if (this.lend.borrower_id.length > 0) {
        this.$api.post('/lendmanagement/lend/create', this.lend);
        this.$go('/lendmanagement/lends');
      } else {
        this.message = this.$t('lendmanagement.lendAdd.missingFields');
        this.$refs.dialog.open();
      }
    },
    onDecode(result) {
      this.decodedText = result;
      this.addItem(result);
      this.beep();
    },
    itemsChange(result) {
      console.log('test');
      // Add
      if(result.length > this.lend.items.length) {
        this.addItem(result[result.length - 1]);
      }
      // Remove
      if (result.length < this.lend.items.length) {
        this.lend.items = this.lend.items.filter(value => result.includes(value.id));
      }
    },
    getIndex(array, id) {
      return array.findIndex(item => {
        console.log(item, id);
        return item.id === id;
      });
    },
    async addItem(result) {
      let item = await this.$api.get('/lendmanagement/item/' + result);
      this.lend.items.push({
        id: item[0].id,
        name: item[0].name,
        qty: 1
      });
    },
    removeItem(id) {
      this.lend.items = this.lend.items.filter(value => value.id !== id);
    },
    beep() {
      let beep = new Audio('https://soundbible.com/mp3/Checkout%20Scanner%20Beep-SoundBible.com-593325210.mp3');
      beep.play();
    },
    addQuantity(id) {
      this.lend.items.forEach(function (value) {
        if (value.id === id) {
          value.qty++;
        }
      });
    },
    removeQuantity(id) {
      this.lend.items.forEach(function (value) {
        if (value.id === id) {
          if (value.qty > 1) {
            value.qty--;
          }
        }
      });
    }
  }
};
</script>

<style>
.k-lend-items {
  width: 100%;
  table-layout: fixed;
  border-spacing: 1px;
}

.k-lend-items td,
.k-lend-items th {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  background: var(--color-white);
}

.k-lend-items-options {
  padding: 0 !important;
  width: 3rem;
  text-align: center;
  overflow: visible !important;
}

.k-button-group {
  justify-content: center !important;
}

.k-lend-items-options button {
  justify-content: end !important;
}
</style>
