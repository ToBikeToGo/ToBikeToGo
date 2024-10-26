import withToast from '../../../components/HOC/WithToastHOC.jsx';
import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { FormBuilder } from '../../../components/Form/FormBuilder.jsx';
import { useEditMember } from './hooks/useEditMember.jsx';
import { useMember } from './hooks/useMember.jsx';
const EditMemberPage = () => {
  const { userDatas, setUserDatas, handleSubmit } = useEditMember();
  const { userId } = useParams();
  const { getMember } = useMember();

  const [schedules, setSchedules] = useState([]);

  useEffect(() => {
    if (userId) {
        getMember(userId).then((data) => {
            console.log('Fetched data:', data); // Pour débugger
            if (data && data.schedules) {
                const mappedSchedules = data.schedules.map(schedule => ({
                    dow: schedule.dow,
                    startTime: new Date(new Date(schedule.startTime).setHours(new Date(schedule.startTime).getHours() - 1)),
                    endTime: new Date(new Date(schedule.endTime).setHours(new Date(schedule.endTime).getHours() - 1)),
                }));
                setSchedules(mappedSchedules);
                setUserDatas((prevData) => ({
                    ...prevData,
                    lastname: data.lastname,
                    firstname: data.firstname,
                    email: data.email,
                    schedules: mappedSchedules,
                }));
            }
        });
    }
}, [getMember, userId]);

  const handleScheduleChange = (updatedSchedules) => {
    console.log('updatedSchedules', updatedSchedules);
    setSchedules(updatedSchedules);
    setUserDatas({ ...userDatas, schedules: updatedSchedules });
  };

  const form = {
    title: 'Edit member',
    canEditAll: true,
    fields: [
      {
        type: 'text',
        id: 'lastname',
        label: 'Last name',
        name: 'lastname',
        value: userDatas.lastname || '',
        isEditable: true,
      },
      {
        type: 'text',
        id: 'firstname',
        label: 'First name',
        name: 'firstname',
        value: userDatas.firstname || '',
        isEditable: true,
      },
      {
        type: 'text',
        id: 'email',
        label: 'Email',
        name: 'email',
        value: userDatas.email || '',
        isEditable: true,
      },
      {
        type: 'schedule-edit', // Garder le type schedule
        id: 'schedules',
        label: 'Schedules',
        name: 'schedules',
        value: schedules, // Passer les horaires récupérés ici
        isEditable: true,
      },
    ],
    submitLabel: 'Update member',
    call: {
      link: `/members/${userId}`,
      method: 'PATCH',
    },
    successMessage: 'Member updated',
  };

  return (
    <FormBuilder
      form={form}
      onChange={(e) => setUserDatas({ ...userDatas, [e.target.name]: e.target.value })}
      onSubmit={handleSubmit}
      onSchedulesChange={handleScheduleChange} // Gérer le changement de horaires ici
      initialSchedules={schedules} // Passer les horaires ici
    />
  );
};

const EditMemberWithToast = withToast(EditMemberPage);

export { EditMemberWithToast as EditMemberPage };
