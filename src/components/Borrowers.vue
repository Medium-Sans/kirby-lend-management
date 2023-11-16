<template>
  <k-inside>
      <k-header>
        {{ $t('lendmanagement.borrowers') }}
        <k-button-group slot="buttons">
          <k-button
            text="New Borrower"
            variant="filled"
            icon="add"
            @click="$dialog('/lendmanagement/borrower/create')"
          />
        </k-button-group>
      </k-header>

      <table class="k-borrowers">

        <tr>
          <th class="k-borrower-options" style="text-align: center">#</th>
          <th>{{ $t('lendmanagement.borrowers.table.firstname') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.lastname') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.email') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.phone') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.lastlend') }}</th>
          <th class="k-borrower-options"></th>
        </tr>

        <tr v-for="(borrower, id) in borrowers" :key="id">
          <td style="text-align: center;">{{ borrower.id }}</td>
          <td>{{ borrower.firstname }}</td>
          <td>{{ borrower.lastname }}</td>
          <td>{{ borrower.email }}</td>
          <td>{{ borrower.phone }}</td>
          <td>{{ formatDate(borrower.lastLend) }}</td>
          <td class="k-borrower-options">
            <k-options-dropdown :options="'lendmanagement/borrower/' + borrower.id" />
          </td>
        </tr>

      </table>
  </k-inside>
</template>

<script>
export default {
  props: {
    borrowers: Object,
    locale: String
  },
  methods: {
    formatDate(dateString) {
      if(!dateString) return '-';
      const date = new Date(dateString);
      // Then specify how you want your dates to be formatted
      return new Intl.DateTimeFormat(this.locale, {dateStyle: 'short'}).format(date);
    }
  }
};
</script>

<style>
.k-borrowers {
  width: 100%;
  table-layout: auto;
  border-spacing: 1px;
}
.k-borrowers td {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  background: var(--color-white);
}

.k-borrowers th {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  border-bottom: 1px solid var(--color-border);
  background: var(--color-gray-100);
}
.k-borrower-options {
  padding: 0 !important;
  width: 3rem;
  overflow: visible !important;
}
</style>
