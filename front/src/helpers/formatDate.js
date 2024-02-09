export const toShortDate = (date) => {
  const newDate = new Date(date);
  return newDate.toLocaleDateString('en-US', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};
