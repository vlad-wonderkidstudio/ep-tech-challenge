// Note - I made it via a method, not a filter, because filters are deprecated in Vue 3
  // and if we will upgrade to Vue 3, we will have to rewrite it anyway
  // Note2 - I return not "to" separator between dates, but locale formatted date range separator (like " - " in English)
  // in order to make it work ok for different languages and locales
export const formatDatesRange = (date1, date2) => {
    const locale = new Intl.DateTimeFormat().resolvedOptions().locale;
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };

    const date1Obj = new Date(date1);
    const date2Obj = new Date(date2);

    const dateTimeFormat = new Intl.DateTimeFormat(locale, options);
    return dateTimeFormat.formatRange(date1Obj, date2Obj);
};

export const formatDate = (date) => {
    const locale = new Intl.DateTimeFormat().resolvedOptions().locale;
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    };

    const dateObj = new Date(date);
    const dateTimeFormat = new Intl.DateTimeFormat(locale, options);
    return dateTimeFormat.format(dateObj);
};
