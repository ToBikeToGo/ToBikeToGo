import { useUserContext } from '../../hooks/UserContext.jsx';

import EN from '../translations/en.json';
import FR from '../translations/fr.json';
import { useCallback, useMemo } from 'react';

export const useTranslation = () => {
  const translations = {
    EN,
    FR,
  };
  const { user } = useUserContext();
  const { locale } = user;

  const getTranslation = useCallback(
    (key) => {
      const translation = translations[locale]?.[key];
      if (translation) {
        return translation;
      }
    },
    [locale, translations]
  );

  return { getTranslation };
};
