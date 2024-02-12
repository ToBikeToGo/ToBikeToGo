import { differenceInDays, parseISO } from 'date-fns';

export const calculateTotalPrice = (dayPrice, startDate, endDate) => {
  const start = parseISO(startDate);
  const end = parseISO(endDate);

  const days = differenceInDays(end, start);

  const totalPrice = dayPrice * days;

  return totalPrice;
};
