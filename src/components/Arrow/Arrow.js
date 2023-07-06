import React, { createRef, useEffect } from "react";
import styles from "./arrow.module.scss";

function Arrow({ className, title, content, arrowClick: harrowClickProp }) {
  const arrowRef = createRef();

  useEffect(() => {
    const optionRow = arrowRef.current.parentElement;
    arrowRef.current.style.top = `${
      optionRow.offsetTop + optionRow.offsetHeight - 10
    }px`;
  }, [arrowRef]);

  function handleArrowClick(e) {
    let index;

    if (content === "-") {
      index = e.target.parentElement.nextElementSibling.dataset.index;
    } else {
      index = e.target.parentElement.dataset.index;
    }
    harrowClickProp(index, content);
  }

  return (
    <div
      className={`${className} ${styles.arrow}`}
      title={title}
      ref={arrowRef}
      onClick={handleArrowClick}
    >
      {content}
    </div>
  );
}

export default Arrow;
