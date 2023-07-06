/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import { createRef, useEffect } from 'react';

function Arrow(props) {
  const {
    className, title, content, arrowClick: harrowClickProp,
  } = props;
  const arrowRef = createRef();

  useEffect(() => {
    const optionRow = arrowRef.current.parentElement;
    arrowRef.current.style.top = `${optionRow.offsetTop + optionRow.offsetHeight - 10}px`;
  }, [arrowRef]);

  function handleArrowClick(e) {
    let index;

    if (content === '-') {
      index = e.target.parentElement.nextElementSibling.dataset.index;
    } else {
      index = e.target.parentElement.dataset.index;
    }
    harrowClickProp(index, content);
  }

  return (
    <div
      className={className}
      title={title}
      ref={arrowRef}
      onClick={handleArrowClick}
    >
      {content}
    </div>
  );
}

export default Arrow;
