import { useUserContext } from '../../hooks/UserContext.jsx';
import EN from '../translations/en.json';
import FR from '../translations/fr.json';
import { useCallback } from 'react';

export const useTranslation = () => {
  const translations = {
    EN,
    FR,
  };
  const { user } = useUserContext();
  const { locale = 'EN' } = user;
  console.log(user, locale);

  const getTranslation = useCallback(
    (key) => {
      const keys = key.split('.');
      const currentTranslations =
        translations[locale.toUpperCase()] || translations['EN'];
      let translation = currentTranslations;
      for (const k of keys) {
        if (translation[k] !== undefined) {
          translation = translation[k];
        } else {
          return `Translation not found for ${key}`;
        }
      }
      return translation;
    },
    [locale]
  );

  return { getTranslation };
};
