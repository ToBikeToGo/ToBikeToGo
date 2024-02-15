import React, { useState } from 'react';
import { Button } from '@mui/material';
import {
  format,
  addMinutes,
  setHours,
  setMinutes,
  isBefore,
  parse,
} from 'date-fns';

const generateTimeSlots = (
  startHour,
  startMinute,
  endHour,
  endMinute,
  interval
) => {
  let times = [];
  let startTime = setMinutes(setHours(new Date(), startHour), startMinute);
  const endTime = setMinutes(setHours(new Date(), endHour), endMinute);

  while (isBefore(startTime, endTime)) {
    times.push(format(startTime, 'HH:mm'));
    startTime = addMinutes(startTime, interval);
  }

  return times;
};

const TimeSlots = ({ unavailableSlots = {}, onChange }) => {
  const [selectedTime, setSelectedTime] = useState(null);
  const timeSlots = generateTimeSlots(9, 0, 18, 0, 30); // de 9h00 à 18h00, intervalle de 30 minutes
  const isUnavailable = (time) => {
    return unavailableSlots[time] === 0 || unavailableSlots[time] === undefined;
  };
  const buttonBaseWidth = timeSlots.length > 10 ? '50%' : '100%';
  const buttonWidth = `calc(${buttonBaseWidth} - 1rem)`;

  const onTimeSlotClick = (time) => {
    if (isUnavailable(time)) {
      return;
    }
    setSelectedTime(time);

    if (onChange) {
      onChange(time);
    }
  };

  return (
    <div
      style={{
        display: 'flex',
        flexWrap: 'wrap',
        justifyContent: 'flex-start',
      }}
    >
      {timeSlots.map((time) => (
        <Button
          key={time}
          style={{
            width: buttonWidth,
            margin: '0.5rem',
            boxSizing: 'border-box',
          }}
          variant={time === selectedTime ? 'contained' : 'outlined'}
          color="black"
          onClick={() => onTimeSlotClick(time)}
          disabled={isUnavailable(time)}
          sx={{
            color: selectedTime === time ? 'white !important' : 'black',
          }}
        >
          {time}
        </Button>
      ))}
    </div>
  );
};
export { TimeSlots };
