import { useEffect, useRef, useState } from 'react';
import { addDays, getDay, isAfter, isEqual } from 'date-fns';

export const useCalendar = ({ onChangeDateCallback, disabledDates = [] }) => {
  const [isOpen, setIsOpen] = useState(false);
  const calendarRef = useRef(null);
  const [dates, setDates] = useState([
    {
      startDate: new Date(),
      endDate: addDays(new Date(), 1),
      key: 'selection',
    },
  ]);

  useEffect(() => {
    document.addEventListener('click', handeInputBlur);
    return () => {
      document.removeEventListener('click', handeInputBlur);
    };
  }, []);

  const onChangeDate = (item) => {
    if (
      getDay(item.selection?.startDate) != getDay(item.selection?.endDate) &&
      getDay(dates[0]?.endDate) !== getDay(item.selection?.endDate)
    ) {
      setIsOpen(false);
    }

    setDates(item?.selection ? [item.selection] : []);
    if (
      isAfter(item.selection?.startDate, item.selection?.endDate) ||
      isEqual(item.selection?.startDate, item.selection?.endDate)
    ) {
      return;
    }
    onChangeDateCallback?.(item?.selection);
  };
  const handleOpen = () => {
    if (!isOpen) setIsOpen(true);
  };
  const handeInputBlur = (e) => {
    // si ce n'est pas un input
    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
      e.preventDefault();
    }

    // if click outside of the calendar
    if (calendarRef.current && !calendarRef.current.contains(e.target)) {
      setIsOpen(false);
    }
  };

  useEffect(() => {
    // document.addEventListener('click', handeInputBlur);
    return () => {
      //document.removeEventListener('click', handeInputBlur);
    };
  }, []);

  return {
    calendarRef,
    dates,
    isOpen,
    onChangeDate,
    handleOpen,
  };
};
