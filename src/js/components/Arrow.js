/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import { createRef, useEffect } from 'react';

function Arrow(props) {
  const {
    className, title, content,
  } = props;
  const arrowRef = createRef();

  useEffect(() => {
    const optionRow = arrowRef.current.parentElement;
    arrowRef.current.style.top = `${optionRow.offsetTop + optionRow.offsetHeight - 10}px`;
    // arrowRef.current.style.top = `${offsetTop + offsetHeight - 10}px`;
  }, []);

  return (
    <div
      className={className}
      title={title}
      ref={arrowRef}
    >
      {content}
    </div>
  );
}

export default Arrow;
