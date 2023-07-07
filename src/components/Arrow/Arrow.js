import React, { useRef, useState, useEffect } from "react";
import styles from "./arrow.module.scss";

function Arrow({
  className,
  title,
  content,
  arrowType,
  arrowClick: harrowClickProp,
}) {
  const [arrowContent, setArrowContent] = useState("+");
  const ref = useRef(null);

  useEffect(() => {
    setArrowContent(() => (arrowType === "+" ? "➕" : "➖"));
  }, [arrowType]);

  function handleArrowClick(e) {
    let index;

    if (arrowType === "-") {
      index = e.target.parentElement.nextElementSibling.dataset.index;
    } else {
      index = e.target.parentElement.dataset.index;
    }
    harrowClickProp(index, arrowType);
  }

  return (
    <button
      type="button"
      className={`${className} ${styles.arrow}`}
      title={title}
      onClick={handleArrowClick}
      ref={ref}
    >
      {arrowContent}
    </button>
  );
}

export default Arrow;
