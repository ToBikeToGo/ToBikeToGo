import { differenceInDays, parseISO } from 'date-fns';

export const calculateTotalPrice = (dayPrice, startDate, endDate) => {
  const start = parseISO(startDate);
  const end = parseISO(endDate);

  console.log(start, end);
  const days = differenceInDays(end, start) + 1;
  console.log(days, dayPrice);
  const totalPrice = dayPrice * days;

  return totalPrice;
};
