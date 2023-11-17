import { Calendar, dateFnsLocalizer } from 'react-big-calendar';
import format from 'date-fns/format';
import parse from 'date-fns/parse';
import startOfWeek from 'date-fns/startOfWeek';
import getDay from 'date-fns/getDay';
import enUS from 'date-fns/locale/en-US';
import './../../style/planning.css';
import theme from '../../theme/theme.js';

const locales = {
  'en-US': enUS,
};

const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek,
  getDay,
  locales,
});
// TODO api call get events for a month by user
const mockedEvents = [
  {
    id: 1,
    title: 'Vacation',
    start: new Date(2023, 9, 7, 10, 0, 0),
    end: new Date(2023, 9, 10, 10, 0, 0),
  },
];

const Planning = ({ events = mockedEvents }) => {
  return (
    <Calendar
      localizer={localizer}
      events={events}
      startAccessor="start"
      endAccessor="end"
      style={{ height: 1000, backgroundColor: theme.palette.primary.main }}
      defaultView="week"
    />
  );
};

export { Planning };
