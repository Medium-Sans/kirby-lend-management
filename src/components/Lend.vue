<template>
  <k-inside>
    <k-view>

      <k-header>
        {{ borrower.firstname }} {{ borrower.lastname }} - {{ lend.start_date }}

        <k-button-group slot="left">
          <k-button icon="archive"
                    :text="$t('lendmanagement.lend.archive')"
                    @click="loanIsBack(`${lend.id})`)"/>

          <k-button icon="refresh"
                    :text="$t('lendmanagement.lend.extend')"
                    @click="$dialog(`lendmanagement/lend/${lend.id}/extend`)"/>

          <k-button icon="undo"
                    :text="$t('lendmanagement.lend.notify')"
                    @click="notify(`${lend.id}`)"/>

          <k-button icon="email"
                    :text="$t('lendmanagement.lend.sendMessage')"
                    @click="$dialog(`lendmanagement/lend/${lend.id}/sendMessage`)"/>
        </k-button-group>
      </k-header>

      <k-grid gutter="large">

        <k-column width="1/2">

          <k-grid gutter="small">

            <k-column width="1/2">
              <k-date-field :help="'Fin du prêt +' + nbr_of_days_added +' jours'" :value=expiry_date :time=false
                            :disabled=true name="date" :label="$t('lendmanagement.lend.form.expiredDate')"/>
            </k-column>

            <k-column width="1/2">
            </k-column>

          </k-grid>

          <k-form v-model="lend" @input="input" @submit="submit" :fields="{
              line: {
                type: 'line'
              }
            }"/>

          <k-form v-model="lend" @input="input" @submit="submit" :fields="{
              start_date: {
                label: $t('lendmanagement.lend.form.startDate'),
                type: 'date',
                time: false,
                disabled: true,
                width: '1/2'
              },
              end_date: {
                label: $t('lendmanagement.lend.form.endDate'),
                type: 'date',
                time: false,
                disabled: true,
                width: '1/2'
              },
              line: {
                type: 'line'
              }
            }"/>

          <k-form v-model="borrower" @input="input" @submit="submit" :fields="{
              firstname: {
                label: $t('lendmanagement.borrowers.table.firstname'),
                type: 'text',
                disabled: true,
                width: '1/2'
              },
              lastname: {
                label: $t('lendmanagement.borrowers.table.lastname'),
                type: 'text',
                disabled: true,
                width: '1/2'
              },
              email: {
                label: $t('lendmanagement.borrowers.table.email'),
                disabled: true,
                type: 'text'
              },
              phone: {
                label: $t('lendmanagement.borrowers.table.phone'),
                disabled: true,
                type: 'text'
              },
            }"/>
        </k-column>

        <k-column width="1/2">
          <table class="k-products">
            <thead>
            <tr>
              <th colspan="3">{{ $t('lendmanagement.items') }}</th>
            </tr>
            </thead>
            <tr>
              <th>{{ $t('lendmanagement.lend.table.name') }}</th>
              <th>{{ $t('lendmanagement.lend.table.quantity') }}</th>
              <th></th>
            </tr>
            <tr v-for="(item, id) in items" :key="id">
              <td>{{ item.title }}</td>
              <td>{{ item.quantity }}</td>
              <td class="k-product-options">
                <k-options-dropdown :options="'lends/' + id"/>
              </td>
            </tr>
          </table>

          <table style="margin-top: 50px;" class="k-products">
            <thead>
            <tr>
              <th colspan="3">{{ $t('lendmanagement.lend.extended') }}</th>
            </tr>
            </thead>
            <tr>
              <th>{{ $t('lendmanagement.lend.extensions.nbrOfDays') }}</th>
              <th>{{ $t('lendmanagement.lend.extensions.addedAt') }}</th>
              <th>{{ $t('lendmanagement.lend.extensions.people') }}</th>
            </tr>
            <tr v-for="(item, id) in extensions" :key="id">
              <td>{{ item.nbr_of_days }}</td>
              <td>{{ item.created_at }}</td>
              <td>{{ item.user }}</td>
            </tr>
          </table>
        </k-column>

      </k-grid>
    </k-view>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Array,
    borrower: Object,
    lend: Object,
    start_date: String,
    end_date: String,
    expiry_date: String,
    extensions: Array,
    nbr_of_days_added: Number,
  },
  data() {
    return {
      lend: {
        id: this.lend.id,
        start_date: this.start_date,
        end_date: this.end_date,
        expiry_date: this.expiry_date,
        nbr_of_days_added: this.nbr_of_days_added,
      },
    }
  },
  methods: {
    loanIsBack() {
      this.$api.post('lendmanagement/lend/' + this.lend.id + '/return', this.lend);
      this.$go('/lendmanagement');
    },
    extend() {
      this.$api.post('lendmanagement/lend/' + this.lend.id + '/extend', this.lend);
      this.$go('/lendmanagement');
    },
    notify() {
      this.$api.post('lendmanagement/lend/' + this.lend.id + '/notify', this.lend);
      this.$go('/lendmanagement');
    },
  }
};
</script>

<style>
.k-headline {
  margin-bottom: 8px;
}
</style>
