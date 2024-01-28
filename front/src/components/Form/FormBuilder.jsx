import Typography from '@mui/material/Typography';
import {
  FormControl,
  InputLabel,
  Select,
  TextField,
  useTheme,
} from '@mui/material';
import Button from '@mui/material/Button';
import Box from '@mui/material/Box';
import withToast from '../HOC/WithToastHOC.jsx';
import { getApirUrl, getMediaUrl } from '../../helpers/getApirUrl.js';
import fetchApi from '../../helpers/fetchApi.js';
import { useState, useRef, useEffect } from 'react';
import { SchedulesChooser } from '../Shchedues/SchedulesChooser.jsx';
import MenuItem from '@mui/material/MenuItem';

const FormBuilder = ({ form, onSubmit, setToast }) => {
  const [datas, setDatas] = useState({});
  const [entity, setEntity] = useState(null);
  const [selectedImage, setSelectedImage] = useState(null);
  const inputFile = useRef(null);
  const apiUrl = getApirUrl();
  const mediaUrl = getMediaUrl();

  const onChangeSchedules = (schedules) => {
    setDatas((prev) => ({
      ...prev,
      schedules: schedules,
    }));
  };

  const handleChange = (e) => {
    console.log('e.target', e.target, datas);
    if (e.target.type === 'file') {
      setDatas({ ...datas, ['file']: e.target.files[0] });
      setSelectedImage(URL.createObjectURL(e.target.files[0]));
    } else {
      setDatas((prev) => ({
        ...prev,
        [e.target.name]: e.target.value,
      }));
    }
  };

  useEffect(() => {
    form.fields.forEach((field) => {
      if (
        field.type === 'text' ||
        field.type === 'hidden' ||
        field.type === 'select'
      ) {
        setDatas((prev) => ({
          ...prev,
          [field.name]: field.value,
        }));
      }
    });
  }, [form.fields]);

  const handleSubmit = (event) => {
    //event.preventDefault();

    if (selectedImage) {
      const formData = new FormData();
      formData.append('file', datas.file);
      fetchApi(`${apiUrl}/medias`, {
        method: 'POST',
        body: formData,
      })
        .then((response) => {
          if (!response.ok) {
            setToast({
              open: true,
              message: 'An error occured',
              severity: 'error',
            });
            return Promise.reject();
          }
          return response.json();
        })
        .then((data) => {
          console.log('data from API', data);
          const updatedDatas = { ...datas, media: data['@id'] };

          console.log('datas', datas);
          return fetchApi(`${apiUrl}${form.call.link}`, {
            method: form.call.method,
            headers: {
              'Content-Type':
                form.call.method === 'PATCH'
                  ? 'application/merge-patch+json'
                  : 'application/json',
            },
            body: JSON.stringify(updatedDatas),
          });
        })
        .then((response) => {
          console.log(response);
          if (!response.ok) {
            console.log('error');
            setToast({
              open: true,
              message: 'An error occured',
              severity: 'error',
            });
            return Promise.reject();
          }
          return response.json();
        })
        .then((data) => {
          console.log('data from API', data);
          setToast({
            open: true,
            message: form?.successMessage,
            severity: 'success',
          });
          onSubmit?.();
        })
        .catch((error) => {});
    } else {
      fetchApi(`${apiUrl}${form.call.link}`, {
        method: form.call.method,
        headers: {
          'Content-Type':
            form.call.method === 'PATCH'
              ? 'application/merge-patch+json'
              : 'application/json',
        },
        body: JSON.stringify(datas),
      })
        .then((response) => {
          console.log(response);
          if (!response.ok) {
            console.log('error');
            setToast({
              open: true,
              message: 'An error occured',
              severity: 'error',
            });
            return Promise.reject();
          }
          return response.json();
        })
        .then((data) => {
          setEntity(data);

          console.log('data from API', data);
          setToast({
            open: true,
            message: form?.successMessage,
            severity: 'success',
          });
          onSubmit?.();
        })
        .catch((error) => {});
    }
  };
  const theme = useTheme();
  return (
    <Box
      component="form"
      onSubmit={handleSubmit}
      sx={{
        mt: 1,
        padding: '2em',

        borderRadius: '12px',
        backgroundColor: theme.palette.common.white,
        margin: '2em',
      }}
      className={'bg-white'}
    >
      <Typography
        component="h1"
        variant="h2"
        sx={{
          textAlign: 'center',
          fontWeight: 'bold',
        }}
      >
        {form.title}
      </Typography>
      {form.fields.map((field) => {
        if (field.type === 'text') {
          return (
            <TextField
              variant="outlined"
              margin="normal"
              required
              fullWidth
              id={field.id}
              label={field.label}
              name={field.name}
              autoFocus
              value={
                datas[field.name] !== undefined
                  ? datas[field.name]
                  : field.value
              }
              onChange={handleChange}
              disabled={!field.isEditable}
            />
          );
        } else if (field.type === 'image') {
          return (
            <input
              accept="image/*"
              id={field.id}
              name={field.name}
              type="file"
              ref={inputFile}
              disabled={!field.isEditable}
              style={{
                margin: '1em 0',
              }}
              onChange={handleChange}
            />
          );
        } else if (field.type === 'schedule') {
          return <SchedulesChooser onChange={onChangeSchedules} />;
        } else if (field.type === 'select') {
          return (
            <FormControl fullWidth>
              <InputLabel>{field.label}</InputLabel>

              <Select
                value={datas[field.name] || ''}
                onChange={handleChange}
                name={field.name}
                fullWidth
                required
              >
                {field.options.map((option) => (
                  <MenuItem value={option.value} key={option.value}>
                    {option.label}
                  </MenuItem>
                ))}
              </Select>
            </FormControl>
          );
        }
      })}
      {selectedImage && (
        <div
          style={{
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            margin: '1em 0',
          }}
        >
          <img
            src={selectedImage}
            alt="Selected"
            style={{
              maxWidth: '100%',
              maxHeight: '200px',
              borderRadius: '12px',
              objectFit: 'cover',
            }}
          />
        </div>
      )}
      <Button type="submit" />
      <Button
        type="submit"
        fullWidth
        variant="contained"
        color="black"
        onClick={handleSubmit}
        sx={{
          mt: 3,
          mb: 2,
          color: theme.palette.common.white,
        }}
      >
        {form.submitLabel}
      </Button>
      <input
        ref={inputFile}
        type="file"
        style={{ display: 'none' }} // hide the actual file input
      />{' '}
    </Box>
  );
};
const FormBuilderWithToast = withToast(FormBuilder);

export { FormBuilderWithToast as FormBuilder };
