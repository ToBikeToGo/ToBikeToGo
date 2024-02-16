import withToast from '../../../components/HOC/WithToastHOC.jsx';
import { FormBuilder } from '../../../components/Form/FormBuilder.jsx';
import Button from '@mui/material/Button';
import { Link, useParams } from 'react-router-dom';
import { ArrowBack } from '@mui/icons-material';
import { SchedulesChooser } from '../../../components/Shchedues/SchedulesChooser.jsx';
import { useShop } from '../../../hooks/UseShop.jsx';
import { useEffect } from 'react';

const AddMemberPage = () => {
  const { shopId } = useParams();

  const { getShopById, shop } = useShop();

  useEffect(() => {
    getShopById(shopId);
  }, [shopId]);

  const form = {
    title: 'Add a member',
    canEditAll: true,
    fields: [
      {
        type: 'text',
        id: 'lastname',
        label: 'Last name',
        name: 'lastname',
        value: '',
        isEditable: true,
      },
      {
        type: 'text',
        id: 'firstname',
        label: 'First name',
        name: 'firstname',
        value: '',
        isEditable: true,
      },
      {
        type: 'text',
        id: 'email',
        label: 'Email',
        name: 'email',
        value: '',
        isEditable: true,
      },
      {
        type: 'hidden',
        id: 'franchise',
        label: 'Shop',
        name: 'franchise',
        value: `${shop?.franchise?.id}`,
      },
      {
        type: 'hidden',
        id: 'shop',
        label: 'Shop',
        name: 'shop',
        value: shopId,
      },
      {
        type: 'schedule',
        relateEntity: 'users',
      },
    ],
    submitLabel: 'Add a member',
    call: {
      link: '/register/member',
      method: 'POST',
    },
    successMessage: 'Member added',
    initialValues: {
      roles: ['ROLE_EMPLOYEE'],
      shop: 90,
      lastname: '',
      firstname: '',
      email: '',
      status: false,
    },
  };

  const onChangeSchedules = (schedules) => {
    console.log(schedules);
  };

  return (
    <>
      <FormBuilder form={form} />
      <Button
        variant="contained"
        color="white"
        type="submit"
        component={Link}
        sx={{
          m: 2,
        }}
        to={`/my-shops/${shopId}`}
      >
        Go back to My Shop
        <ArrowBack />
      </Button>
    </>
  );
};

const AddMemberWithToast = withToast(AddMemberPage);

export { AddMemberWithToast as AddMemberPage };
